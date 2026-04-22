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
            foreach ($settings as $r) DB::table('settings')->updateOrInsert(['key'=>$r['key']], $r);
        }
        if (Schema::hasTable('email_templates')) {
            DB::table('email_templates')->updateOrInsert(['key'=>'order.confirmation'], [
                'name'=>'Order Confirmation',
                'subject'=>'অর্ডার কনফার্ম — {{order_no}} — {{site_name}}',
                'body'=>'<div style="font-family:Arial,sans-serif;max-width:600px;margin:auto;background:#fff;border:1px solid #eee;border-radius:14px;overflow:hidden"><div style="background:linear-gradient(135deg,#c0392b,#e8671a);padding:26px;color:#fff;text-align:center"><h1 style="margin:0;font-size:24px">🎉 ধন্যবাদ, {{name}}!</h1><p style="margin:8px 0 0">আপনার অর্ডার আমরা পেয়ে গেছি।</p></div><div style="padding:26px;color:#2a1d18;line-height:1.7"><p><b>অর্ডার নং:</b> {{order_no}}</p><p><b>মোট:</b> ৳{{total}}</p><p>খুব শীঘ্রই আমরা আপনার সাথে যোগাযোগ করবো।</p><p style="margin-top:24px;color:#888;font-size:12px">— {{site_name}} টিম</p></div></div>',
                'description'=>'Sent automatically after checkout. Vars: name, order_no, total, site_name',
                'is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
            ]);
            DB::table('email_templates')->updateOrInsert(['key'=>'marketing.offer'], [
                'name'=>'Marketing Offer Broadcast',
                'subject'=>'🔥 {{site_name}} — আজকের স্পেশাল অফার!',
                'body'=>'<div style="font-family:Arial,sans-serif;max-width:600px;margin:auto"><div style="background:linear-gradient(135deg,#c0392b,#e8671a);padding:40px;color:#fff;text-align:center;border-radius:16px 16px 0 0"><h1 style="margin:0;font-size:34px">10% ছাড়!</h1><p style="margin:8px 0 0;font-size:18px">শুধু আজকের জন্য</p></div><div style="background:#fff;padding:32px;border-radius:0 0 16px 16px;border:1px solid #eee;border-top:0"><p>প্রিয় {{name}},</p><p>আজকেই অর্ডার করুন এবং পেয়ে যান <b>10% ছাড়</b>! কুপন কোড: <code style="background:#fff0d6;padding:5px 10px;border-radius:7px">SAVE10</code></p><p style="text-align:center;margin:32px 0"><a href="#" style="background:#c0392b;color:#fff;padding:13px 34px;border-radius:999px;text-decoration:none;font-weight:bold">এখনই অর্ডার করুন →</a></p><p style="color:#888;font-size:12px">— {{site_name}}</p></div></div>',
                'description'=>'Offer/promo email for admin broadcast. Vars: name, site_name',
                'is_active'=>true,'created_at'=>now(),'updated_at'=>now(),
            ]);
        }
    }
    public function down(): void {}
};
