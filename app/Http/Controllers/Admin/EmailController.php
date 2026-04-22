<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GenericMail;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function create()
    {
        $templates = EmailTemplate::where('is_active', true)->orderBy('name')->get();
        $userCount = User::count();
        $customerCount = User::where('is_admin', false)->count();
        return view('admin.emails.send', compact('templates', 'userCount', 'customerCount'));
    }

    public function history(Request $request)
    {
        $logs = EmailLog::with(['template', 'sender'])->latest('sent_at')->latest()
            ->when($request->filled('q'), fn($q) => $q->where(function ($x) use ($request) {
                $x->where('recipient_email', 'like', '%' . $request->q . '%')
                  ->orWhere('subject', 'like', '%' . $request->q . '%');
            }))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->paginate(25)->withQueryString();
        return view('admin.emails.history', compact('logs'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'template_key' => 'required|exists:email_templates,key',
            'audience' => 'required|in:single,all_customers,all_users,custom',
            'email' => 'required_if:audience,single|nullable|email',
            'custom_list' => 'required_if:audience,custom|nullable|string',
            'subject_override' => 'nullable|string|max:200',
        ]);

        $recipients = match ($data['audience']) {
            'single' => [['email' => $data['email'], 'name' => 'Customer']],
            'all_customers' => User::where('is_admin', false)->whereNotNull('email')->get(['email','name'])->map(fn($u)=>['email'=>$u->email,'name'=>$u->name])->toArray(),
            'all_users' => User::whereNotNull('email')->get(['email','name'])->map(fn($u)=>['email'=>$u->email,'name'=>$u->name])->toArray(),
            'custom' => collect(preg_split('/[\s,;]+/', $data['custom_list']))->filter(fn($e)=>filter_var($e, FILTER_VALIDATE_EMAIL))->unique()->map(fn($e)=>['email'=>$e,'name'=>'Customer'])->values()->toArray(),
        };

        if (empty($recipients)) return back()->with('error', 'কোনো রিসিপিয়েন্ট পাওয়া যায়নি।')->withInput();

        $sent = 0; $failed = 0;
        foreach ($recipients as $r) {
            $rendered = EmailTemplate::render($data['template_key'], ['name' => $r['name'] ?: 'Customer']);
            if (!$rendered) continue;
            $subject = $data['subject_override'] ?: $rendered['subject'];
            $log = EmailLog::create([
                'email_template_id' => $rendered['template_id'] ?? null,
                'recipient_email' => $r['email'],
                'recipient_name' => $r['name'] ?? null,
                'subject' => $subject,
                'audience' => $data['audience'],
                'status' => 'pending',
                'sent_by' => auth()->id(),
            ]);
            try {
                Mail::to($r['email'])->send(new GenericMail($subject, $rendered['body']));
                $log->update(['status' => 'sent', 'sent_at' => now()]);
                $sent++;
            } catch (\Throwable $e) {
                $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
                $failed++;
            }
        }
        return redirect()->route('admin.emails.history')->with('toast', "✅ {$sent} টি ইমেইল পাঠানো হয়েছে" . ($failed ? " ({$failed} ব্যর্থ)" : ''));
    }
}
