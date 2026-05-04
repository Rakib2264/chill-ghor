<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GenericMail;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class EmailController extends Controller
{
    // সেন্ড পেইজ দেখানোর জন্য
    public function create()
    {
        $templates = EmailTemplate::where('is_active', true)->orderBy('name')->get();
        $userCount = User::count();
        $customerCount = User::where('is_admin', false)->count();
        return view('admin.emails.send', compact('templates', 'userCount', 'customerCount'));
    }

    // ইমেইল প্রিভিউ দেখানোর জন্য (AJAX)
    public function preview(Request $request)
    {
        $request->validate([
            'key' => 'required|exists:email_templates,key',
            'subject_override' => 'nullable|string',
            'vars' => 'nullable|array',
        ]);

        // ডিফল্ট ভেরিয়েবল
        $defaultVars = [
            'name' => 'Demo User',
            'site_name' => Setting::get('site_name', 'Chill Ghor'),
            'order_link' => url('/menu'),
            'cart_link' => url('/cart'),
            'review_link' => url('/'),
            'reset_link' => url('/password/reset'),
            'offer_text' => 'স্পেশাল অফার!',
            'favorite_item' => 'আপনার পছন্দের আইটেম',
            'last_order_item' => 'আপনার শেষ অর্ডার',
            'order_no' => 'ORD-12345',
            'total' => '৳৫০০',
            'delivery_time' => '৩০-৪৫ মিনিট',
        ];

        // ইউজারের ভেরিয়েবল এর সাথে মার্জ
        $vars = array_merge($defaultVars, $request->input('vars', []));

        $rendered = EmailTemplate::render($request->key, $vars);

        if (!$rendered) {
            return response()->json(['body' => '<p>টেমপ্লেট রেন্ডার করতে সমস্যা হয়েছে</p>', 'subject' => '']);
        }

        $subject = $request->subject_override ?: $rendered['subject'];

        return response()->json([
            'body' => $rendered['body'],
            'subject' => $subject
        ]);
    }

    // ইমেইল হিস্ট্রি
    public function history(Request $request)
    {
        $logs = EmailLog::with(['template', 'sender'])->latest('sent_at')->latest()
            ->when($request->filled('q'), fn($q) => $q->where(function ($x) use ($request) {
                $x->where('recipient_email', 'like', '%' . $request->q . '%')
                    ->orWhere('subject', 'like', '%' . $request->q . '%');
            }))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('template'), fn($q) => $q->where('email_template_id', $request->template))
            ->when($request->filled('audience'), fn($q) => $q->where('audience', $request->audience))
            ->paginate(25)->withQueryString();

        $templateList = EmailTemplate::orderBy('name')->get();
        $stats = [
            'total' => EmailLog::count(),
            'sent' => EmailLog::where('status', 'sent')->count(),
            'failed' => EmailLog::where('status', 'failed')->count(),
            'pending' => EmailLog::where('status', 'pending')->count(),
        ];

        return view('admin.emails.history', compact('logs', 'templateList', 'stats'));
    }

    // ইমেইল লগ প্রিভিউ
    public function previewLog($id)
    {
        $log = EmailLog::with('template')->findOrFail($id);

        // ইমেইল বডি তৈরি (ভেরিয়েবল রিপ্লেস সহ)
        $body = $log->template ? $log->template->body : '<p>টেমপ্লেট পাওয়া যায়নি</p>';

        // বেসিক ভেরিয়েবল রিপ্লেস
        $vars = [
            'name' => $log->recipient_name ?? 'Valued Customer',
            'site_name' => Setting::get('site_name', 'Chill Ghor'),
            'order_link' => url('/'),
        ];

        foreach ($vars as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
        }

        return response()->json([
            'body' => $body,
            'subject' => $log->subject
        ]);
    }

    // ইমেইল রিসেন্ড
    public function resend($id)
    {
        $log = EmailLog::with('template')->findOrFail($id);

        if (!$log->template) {
            return back()->with('error', 'টেমপ্লেট পাওয়া যায়নি।');
        }

        try {
            $vars = ['name' => $log->recipient_name ?: 'Valued Customer'];
            $rendered = EmailTemplate::render($log->template->key, $vars);

            if ($rendered) {
                Mail::to($log->recipient_email)->send(new GenericMail($log->subject, $rendered['body']));
                $log->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                    'error_message' => null
                ]);
                return back()->with('toast', '✅ ইমেইল পুনরায় পাঠানো হয়েছে');
            } else {
                throw new \Exception('টেমপ্লেট রেন্ডার করতে পারেনি');
            }
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
            return back()->with('error', 'ইমেইল পাঠাতে ব্যর্থ: ' . $e->getMessage());
        }
    }

    // ইউজার সার্চ (AJAX)
    public function searchUsers(Request $request)
    {
        $q = $request->input('q');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $users = User::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id', 'name', 'email']);

        // অ্যাভাটার ফিল্ড যোগ করুন
        $users->transform(function ($user) {
            $user->avatar = $user->avatar ?? '/images/default-avatar.png';
            return $user;
        });

        return response()->json($users);
    }

    // ইমেইল সেন্ড করার মেইন ফাংশন
    public function send(Request $request)
    {
        $data = $request->validate([
            'template_key' => 'required|exists:email_templates,key',
            'audience' => 'required|in:single,selected,all_customers,all_users,custom',
            'email' => 'required_if:audience,single|nullable|email',
            'selected_users' => 'required_if:audience,selected|nullable|array',
            'selected_users.*' => 'exists:users,id',
            'custom_list' => 'required_if:audience,custom|nullable|string',
            'subject_override' => 'nullable|string|max:200',
            'vars' => 'nullable|array',
        ]);

        // রেসিপিয়েন্ট লিস্ট তৈরি
        $recipients = match ($data['audience']) {
            'single' => [['email' => $data['email'], 'name' => null]],
            'selected' => User::whereIn('id', $data['selected_users'] ?? [])
                ->get(['email', 'name'])
                ->map(fn($u) => ['email' => $u->email, 'name' => $u->name])
                ->toArray(),
            'all_customers' => User::where('is_admin', false)
                ->whereNotNull('email')
                ->get(['email', 'name'])
                ->map(fn($u) => ['email' => $u->email, 'name' => $u->name])
                ->toArray(),
            'all_users' => User::whereNotNull('email')
                ->get(['email', 'name'])
                ->map(fn($u) => ['email' => $u->email, 'name' => $u->name])
                ->toArray(),
            'custom' => collect(preg_split('/[\s,;]+/', $data['custom_list']))
                ->filter(fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
                ->unique()
                ->map(fn($e) => ['email' => $e, 'name' => null])
                ->values()
                ->toArray(),
        };

        if (empty($recipients)) {
            return back()->with('error', 'কোনো রেসিপিয়েন্ট পাওয়া যায়নি।')->withInput();
        }

        $sent = 0;
        $failed = 0;
        $errors = [];

        foreach ($recipients as $recipient) {
            // নাম সেট করুন
            $name = $recipient['name'] ?? explode('@', $recipient['email'])[0];

            // ভেরিয়েবল প্রিপেয়ার করুন
            $vars = array_merge(
                ['name' => $name],
                $data['vars'] ?? []
            );

            // টেমপ্লেট রেন্ডার করুন
            $rendered = EmailTemplate::render($data['template_key'], $vars);

            if (!$rendered) {
                $failed++;
                $errors[] = "টেমপ্লেট {$data['template_key']} রেন্ডার করতে পারেনি";
                continue;
            }

            $subject = $data['subject_override'] ?: $rendered['subject'];

            // লগ তৈরি করুন
            $log = EmailLog::create([
                'email_template_id' => $rendered['template_id'] ?? null,
                'recipient_email' => $recipient['email'],
                'recipient_name' => $name,
                'subject' => $subject,
                'audience' => $data['audience'],
                'status' => 'pending',
                'sent_by' => auth()->id(),
            ]);

            try {
                // ইমেইল পাঠান
                Mail::to($recipient['email'])->send(new GenericMail($subject, $rendered['body']));
                $log->update(['status' => 'sent', 'sent_at' => now()]);
                $sent++;
            } catch (\Throwable $e) {
                $log->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage()
                ]);
                $failed++;
                $errors[] = $recipient['email'] . ': ' . $e->getMessage();
            }
        }

        $message = "✅ {$sent} টি ইমেইল পাঠানো হয়েছে";
        if ($failed > 0) {
            $message .= " ❌ {$failed} টি ব্যর্থ হয়েছে";
        }

        if (!empty($errors) && $failed > 0) {
            return redirect()->route('admin.emails.history')
                ->with('toast', $message)
                ->with('errors', $errors);
        }

        return redirect()->route('admin.emails.history')->with('toast', $message);
    }
}
