<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\EmailLog;
use App\Mail\GenericMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'ইমেইল অথবা পাসওয়ার্ড সঠিক নয়।',
            ]);
        }

        $user = Auth::user();

        // Check if last_login_at column exists
        if (Schema::hasColumn('users', 'last_login_at')) {
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);
        }

        $request->session()->regenerate();

        if ($user->is_admin) {
            return redirect()->intended(route('admin.dashboard'))->with('toast', 'স্বাগতম অ্যাডমিন!');
        }

        return redirect()->intended(route('home'))->with('toast', 'স্বাগতম, ' . $user->name . '!');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:120',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'address'  => 'nullable|string|max:500',
        ], [
            'email.unique' => 'এই ইমেইল দিয়ে আগেই অ্যাকাউন্ট তৈরি করা হয়েছে',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে',
            'password.confirmed' => 'পাসওয়ার্ড মিলছে না',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'address'  => $data['address'] ?? null,
            'password' => Hash::make($data['password']),
            'is_admin' => false,
        ]);

        // ✅✅✅ রেজিস্ট্রেশনের পর ইমেইল পাঠান ✅✅✅
        $this->sendWelcomeEmail($user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('toast', '✅ অ্যাকাউন্ট তৈরি হয়েছে! একটি স্বাগত ইমেইল পাঠানো হয়েছে। স্বাগতম, ' . $user->name);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('toast', 'আপনি লগআউট করেছেন');
    }

    /**
     * নতুন ইউজারকে স্বাগত ইমেইল পাঠান
     */
    private function sendWelcomeEmail(User $user)
    {
        try {
            // ইমেইল ভ্যালিডেশন
            if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                Log::warning('Invalid email for welcome email', ['email' => $user->email]);
                return;
            }

            Log::info('Sending welcome email to: ' . $user->email);

            // টেমপ্লেট রেন্ডার করুন
            $rendered = EmailTemplate::render('welcome.email', [
                'name' => $user->name,
            ]);

            // যদি টেমপ্লেট না পাওয়া যায়, ডিফল্ট টেমপ্লেট ব্যবহার করুন
            if (!$rendered) {
                Log::warning('Welcome email template not found, using default');
                $rendered = $this->getDefaultWelcomeTemplate($user);
            }

            if (!$rendered) {
                Log::error('Failed to render welcome email template');
                return;
            }

            // ইমেইল লগ তৈরি করুন
            $log = EmailLog::create([
                'email_template_id' => $rendered['template_id'] ?? null,
                'recipient_email' => $user->email,
                'recipient_name' => $user->name,
                'subject' => $rendered['subject'],
                'audience' => 'welcome',
                'status' => 'pending',
                'sent_by' => null,
            ]);

            // ইমেইল পাঠান
            Mail::to($user->email)->send(new GenericMail($rendered['subject'], $rendered['body']));

            // লগ আপডেট করুন
            $log->update(['status' => 'sent', 'sent_at' => now()]);

            Log::info('Welcome email sent successfully to: ' . $user->email);
        } catch (\Throwable $e) {
            Log::error('Welcome email failed for ' . $user->email . ': ' . $e->getMessage());

            if (isset($log)) {
                $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            }
        }
    }

    /**
     * ডিফল্ট ওয়েলকাম টেমপ্লেট (যদি ডাটাবেসে না থাকে)
     */
    private function getDefaultWelcomeTemplate(User $user): array
    {
        $siteName = config('app.name', 'Chill Ghor');

        $subject = "স্বাগতম {$user->name}! — {$siteName}";

        $body = <<<HTML
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #eee;">
  <div style="background: linear-gradient(135deg, #c0392b, #e8671a); padding: 40px 30px; text-align: center; color: white;">
    <h1 style="margin: 0; font-size: 28px;">🎉 স্বাগতম, {$user->name}!</h1>
    <p style="margin: 10px 0 0; opacity: 0.95;">আপনি এখন {$siteName} পরিবারের সদস্য</p>
  </div>
  <div style="padding: 30px; color: #2a1d18; line-height: 1.6;">
    <p>প্রিয় <strong>{$user->name}</strong>,</p>
    <p>আপনার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে। এখন আপনি উপভোগ করতে পারবেন:</p>
    <ul style="margin: 15px 0; padding-left: 20px;">
      <li>✅ অর্ডার হিস্ট্রি ট্র্যাক</li>
      <li>✅ একাধিক ঠিকানা সংরক্ষণ</li>
      <li>✅ দ্রুত চেকআউট</li>
      <li>✅ এক্সক্লুসিভ অফার</li>
    </ul>
    <p>আপনার প্রথম অর্ডারে <strong>১০% ছাড়</strong> পেতে কোড: <code style="background: #fef3c7; padding: 4px 8px; border-radius: 8px;">WELCOME10</code></p>
    <div style="text-align: center; margin: 30px 0;">
      <a href="{$siteName}/menu" style="background: #c0392b; color: white; padding: 12px 30px; border-radius: 999px; text-decoration: none; font-weight: bold; display: inline-block;">🍽️ অর্ডার করুন</a>
    </div>
    <p style="margin-top: 30px; font-size: 12px; color: #888;">— {$siteName} টিম</p>
  </div>
</div>
HTML;

        return [
            'subject' => $subject,
            'body' => $body,
            'template_id' => null,
        ];
    }
}
