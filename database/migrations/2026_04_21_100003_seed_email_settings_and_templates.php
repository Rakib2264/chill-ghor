<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $settings = [
            ['key'=>'mail_host','value'=>'smtp.gmail.com','type'=>'string','group'=>'email','label'=>'SMTP Host'],
            ['key'=>'mail_port','value'=>'587','type'=>'string','group'=>'email','label'=>'SMTP Port'],
            ['key'=>'mail_username','value'=>'','type'=>'string','group'=>'email','label'=>'SMTP Username'],
            ['key'=>'mail_password','value'=>'','type'=>'string','group'=>'email','label'=>'SMTP Password / App Password'],
            ['key'=>'mail_encryption','value'=>'tls','type'=>'string','group'=>'email','label'=>'Encryption'],
            ['key'=>'mail_from','value'=>'noreply@chillghor.test','type'=>'string','group'=>'email','label'=>'From Address'],
            ['key'=>'mail_from_name','value'=>'Chill Ghor','type'=>'string','group'=>'email','label'=>'From Name'],
        ];
        
        if (Schema::hasTable('settings')) {
            foreach ($settings as $r) {
                DB::table('settings')->updateOrInsert(['key'=>$r['key']], $r);
            }
        }
        
        if (Schema::hasTable('email_templates')) {
            // ১. অর্ডার কনফার্মেশন টেমপ্লেট
            DB::table('email_templates')->updateOrInsert(['key'=>'order.confirmation'], [
                'name'=>'Order Confirmation',
                'subject'=>'অর্ডার কনফার্ম — {{order_no}} — {{site_name}}',
                'body'=>'<div style="font-family:Arial,sans-serif;max-width:600px;margin:auto;background:#fff;border:1px solid #eee;border-radius:14px;overflow:hidden"><div style="background:linear-gradient(135deg,#c0392b,#e8671a);padding:26px;color:#fff;text-align:center"><h1 style="margin:0;font-size:24px">🎉 ধন্যবাদ, {{name}}!</h1><p style="margin:8px 0 0">আপনার অর্ডার আমরা পেয়ে গেছি।</p></div><div style="padding:26px;color:#2a1d18;line-height:1.7"><p><b>অর্ডার নং:</b> {{order_no}}</p><p><b>মোট:</b> ৳{{total}}</p><p>খুব শীঘ্রই আমরা আপনার সাথে যোগাযোগ করবো।</p><p style="margin-top:24px;color:#888;font-size:12px">— {{site_name}} টিম</p></div></div>',
                'description'=>'Sent automatically after checkout. Vars: name, order_no, total, site_name',
                'is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
            ]);
            
            // ২. মার্কেটিং অফার টেমপ্লেট
            DB::table('email_templates')->updateOrInsert(['key'=>'marketing.offer'], [
                'name'=>'Marketing Offer Broadcast',
                'subject'=>'🔥 {{site_name}} — আজকের স্পেশাল অফার!',
                'body'=>'<div style="font-family:Arial,sans-serif;max-width:600px;margin:auto"><div style="background:linear-gradient(135deg,#c0392b,#e8671a);padding:40px;color:#fff;text-align:center;border-radius:16px 16px 0 0"><h1 style="margin:0;font-size:34px">10% ছাড়!</h1><p style="margin:8px 0 0;font-size:18px">শুধু আজকের জন্য</p></div><div style="background:#fff;padding:32px;border-radius:0 0 16px 16px;border:1px solid #eee;border-top:0"><p>প্রিয় {{name}},</p><p>আজকেই অর্ডার করুন এবং পেয়ে যান <b>10% ছাড়</b>! কুপন কোড: <code style="background:#fff0d6;padding:5px 10px;border-radius:7px">SAVE10</code></p><p style="text-align:center;margin:32px 0"><a href="#" style="background:#c0392b;color:#fff;padding:13px 34px;border-radius:999px;text-decoration:none;font-weight:bold">এখনই অর্ডার করুন →</a></p><p style="color:#888;font-size:12px">— {{site_name}}</p></div></div>',
                'description'=>'Offer/promo email for admin broadcast. Vars: name, site_name',
                'is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
            ]);
            
            // ৩. ✅ নিউ: ওয়েলকাম ইমেইল টেমপ্লেট (রেজিস্ট্রেশনের জন্য)
            DB::table('email_templates')->updateOrInsert(['key'=>'welcome.email'], [
                'name'=>'Welcome Email',
                'subject'=>'স্বাগতম {{name}}! — {{site_name}}',
                'body'=>'<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #eee;">
                    <div style="background: linear-gradient(135deg, #c0392b, #e8671a); padding: 40px 30px; text-align: center; color: white;">
                        <h1 style="margin: 0; font-size: 28px;">🎉 স্বাগতম, {{name}}!</h1>
                        <p style="margin: 10px 0 0;">আপনি এখন {{site_name}} পরিবারের সদস্য</p>
                    </div>
                    <div style="padding: 30px; color: #2a1d18; line-height: 1.6;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আপনার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে। এখন আপনি উপভোগ করতে পারবেন:</p>
                        <ul style="margin: 15px 0; padding-left: 20px;">
                            <li>✅ অর্ডার হিস্ট্রি ট্র্যাক</li>
                            <li>✅ একাধিক ঠিকানা সংরক্ষণ</li>
                            <li>✅ দ্রুত চেকআউট</li>
                            <li>✅ এক্সক্লুসিভ অফার</li>
                        </ul>
                        <p>আপনার প্রথম অর্ডারে <strong>১০% ছাড়</strong> পেতে কোড: <code style="background: #fef3c7; padding: 6px 12px; border-radius: 8px;">WELCOME10</code></p>
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{ route("menu.index") }}" style="background: #c0392b; color: white; padding: 12px 30px; border-radius: 999px; text-decoration: none; font-weight: bold;">🍽️ অর্ডার করুন</a>
                        </div>
                        <p style="margin-top: 30px; font-size: 12px; color: #888;">— {{site_name}} টিম</p>
                    </div>
                </div>',
                'description'=>'Welcome email sent after user registration. Vars: name, site_name',
                'is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
            ]);
            
            // ৪. ✅ নিউ: পাসওয়ার্ড রিসেট টেমপ্লেট
            DB::table('email_templates')->updateOrInsert(['key'=>'password.reset'], [
                'name'=>'Password Reset',
                'subject'=>'পাসওয়ার্ড রিসেট করুন — {{site_name}}',
                'body'=>'<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #eee;">
                    <div style="background: #c0392b; padding: 20px; text-align: center; color: white;">
                        <h1 style="margin: 0; font-size: 24px;">🔐 পাসওয়ার্ড রিসেট</h1>
                    </div>
                    <div style="padding: 30px; color: #2a1d18;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আমরা আপনার অ্যাকাউন্টের জন্য একটি পাসওয়ার্ড রিসেট রিকোয়েস্ট পেয়েছি।</p>
                        <p>আপনার পাসওয়ার্ড রিসেট করতে নিচের বাটনে ক্লিক করুন:</p>
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{reset_link}}" style="background: #c0392b; color: white; padding: 12px 35px; border-radius: 999px; text-decoration: none; font-weight: bold;">🔑 পাসওয়ার্ড রিসেট করুন</a>
                        </div>
                        <p>যদি আপনি এই রিকোয়েস্টটি করেননি, তাহলে এই ইমেইলটি উপেক্ষা করুন।</p>
                        <p style="margin-top: 30px; font-size: 12px; color: #888;">— {{site_name}} সাপোর্ট টিম</p>
                    </div>
                </div>',
                'description'=>'Password reset email with reset link. Vars: name, reset_link, site_name',
                'is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
            ]);
            
            // ৫. ✅ নিউ: অর্ডার ডেলিভারি টেমপ্লেট
            DB::table('email_templates')->updateOrInsert(['key'=>'order.delivered'], [
                'name'=>'Order Delivered',
                'subject'=>'আপনার অর্ডার ডেলিভারি হয়েছে! — {{order_no}}',
                'body'=>'<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #eee;">
                    <div style="background: linear-gradient(135deg, #27ae60, #2ecc71); padding: 30px; text-align: center; color: white;">
                        <h1 style="margin: 0; font-size: 28px;">✅ অর্ডার ডেলিভারি হয়েছে!</h1>
                        <p style="margin: 10px 0 0;">অর্ডার নং: {{order_no}}</p>
                    </div>
                    <div style="padding: 30px;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আপনার অর্ডারটি সফলভাবে ডেলিভারি করা হয়েছে।</p>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 12px; margin: 20px 0;">
                            <p><strong>📦 অর্ডার তথ্য:</strong></p>
                            <p>অর্ডার নং: {{order_no}}<br>
                            মোট মূল্য: ৳{{total}}<br>
                            ডেলিভারি সময়: {{delivery_time}}</p>
                        </div>
                        <p>আপনার মূল্যবান ফিডব্যাক জানাতে ভুলবেন না!</p>
                        <div style="text-align: center; margin: 20px 0;">
                            <a href="{{review_link}}" style="background: #27ae60; color: white; padding: 10px 25px; border-radius: 999px; text-decoration: none;">⭐ রিভিউ দিন</a>
                            <a href="{{order_link}}" style="background: #666; color: white; padding: 10px 25px; border-radius: 999px; text-decoration: none;">📋 অর্ডার দেখুন</a>
                        </div>
                        <p style="font-size: 12px; color: #888;">— {{site_name}} টিম</p>
                    </div>
                </div>',
                'description'=>'Sent when order is delivered. Vars: name, order_no, total, delivery_time, review_link, order_link, site_name',
                'is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
            ]);
            
            // ৬. ✅ নিউ: নিউস্লেটার সাবস্ক্রিপশন টেমপ্লেট
            DB::table('email_templates')->updateOrInsert(['key'=>'newsletter.welcome'], [
                'name'=>'Newsletter Welcome',
                'subject'=>'আপনি {{site_name}} নিউজলেটারে সাবস্ক্রাইব করেছেন!',
                'body'=>'<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #eee;">
                    <div style="background: #c0392b; padding: 25px; text-align: center; color: white;">
                        <h1 style="margin: 0; font-size: 30px;">📧 {{site_name}} নিউজলেটার</h1>
                    </div>
                    <div style="padding: 30px;">
                        <p>প্রিয় <strong>{{email}}</strong>,</p>
                        <p>আপনাকে {{site_name}} নিউজলেটারে স্বাগতম! 🎉</p>
                        <p>আমরা আপনাকে পাঠাবো:</p>
                        <ul style="padding-left: 20px;">
                            <li>✨ সাপ্তাহিক স্পেশাল অফার</li>
                            <li>🎂 জন্মদিন ও অ্যানিভার্সারি ডিসকাউন্ট</li>
                            <li>🆕 নতুন আইটেম আপডেট</li>
                            <li>🎯 এক্সক্লুসিভ প্রোমো কোড</li>
                        </ul>
                        <div style="background: #fef3c7; padding: 12px; border-radius: 10px; margin: 20px 0; text-align: center;">
                            <p style="margin: 0;"><strong>🎁 এক্সক্লুসিভ অফার!</strong></p>
                            <p style="margin: 5px 0;">আপনার প্রথম অর্ডারে ১৫% ছাড়</p>
                            <code style="background: white; padding: 5px 10px; border-radius: 6px;">NEWS15</code>
                        </div>
                        <p style="font-size: 12px; color: #888;">আপনি যেকোনো সময় আনসাবস্ক্রাইব করতে পারেন।</p>
                        <p>— {{site_name}} টিম</p>
                    </div>
                </div>',
                'description'=>'Welcome email for newsletter subscribers. Vars: email, site_name',
                'is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
            ]);
        }
    }
    
    public function down(): void 
    {
        if (Schema::hasTable('email_templates')) {
            DB::table('email_templates')->whereIn('key', [
                'welcome.email',
                'password.reset', 
                'order.delivered',
                'newsletter.welcome'
            ])->delete();
        }
    }
};