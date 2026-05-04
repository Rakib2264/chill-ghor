<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [

            // 🔥 Flash Offer / Urgent Marketing
            [
                'key' => 'marketing.flash_offer',
                'name' => 'Flash Offer (Urgent)',
                'subject' => '🚨 অফার চলছে! এখনই চলে আসুন — {{site_name}}',
                'body' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #eee;">
                    <div style="background: linear-gradient(135deg, #ff0000, #ff7b00); padding: 30px; text-align: center; color: white;">
                        <h1 style="margin: 0; font-size: 30px;">🔥 অফার চলছে!</h1>
                        <p style="margin: 10px 0 0;">মিস করলে কিন্তু লস 😱</p>
                    </div>
                    <div style="padding: 30px; color: #2a1d18;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>এখনই চলে আসুন! {{site_name}}-এ চলছে <strong>স্পেশাল অফার</strong> 🎉</p>
                        <div style="background: #fff3cd; padding: 15px; border-radius: 10px; margin: 20px 0; text-align: center;">
                            <p style="margin: 0;"><strong>💥 আজকের অফার:</strong></p>
                            <p style="margin: 5px 0;">{{offer_text}}</p>
                        </div>
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{order_link}}" style="background: #c0392b; color: white; padding: 12px 30px; border-radius: 999px; text-decoration: none; font-weight: bold;">🍔 এখনই অর্ডার করুন</a>
                        </div>
                        <p style="color: #888; font-size: 12px;">⏳ সীমিত সময়ের জন্য</p>
                        <p style="font-size: 12px; color: #888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Urgent flash offer email. Vars: name, offer_text, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🍔 Come Now (Restaurant Call)
            [
                'key' => 'marketing.come_now',
                'name' => 'Come Now Offer',
                'subject' => '🍽️ অফার চলছে — এখনই চলে আসুন!',
                'body' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #fff; border-radius: 16px; border: 1px solid #eee;">
                    <div style="background: #c0392b; padding: 25px; text-align: center; color: white;">
                        <h1 style="margin: 0;">😋 আজকে কিছু স্পেশাল!</h1>
                    </div>
                    <div style="padding: 30px;">
                        <p>হ্যালো <strong>{{name}}</strong>,</p>
                        <p>আজ {{site_name}}-এ চলছে দারুণ অফার 🎉</p>
                        <ul style="padding-left: 20px;">
                            <li>🔥 ফুচকা / চটপটি স্পেশাল</li>
                            <li>🍗 ফ্রাইড চিকেন কম্বো</li>
                            <li>🥤 ফ্রি ড্রিংকস অফার</li>
                        </ul>
                        <p>👉 দেরি না করে এখনই চলে আসুন অথবা অর্ডার করুন!</p>
                        <div style="text-align: center; margin: 25px 0;">
                            <a href="{{order_link}}" style="background: #27ae60; color: white; padding: 12px 30px; border-radius: 999px; text-decoration: none;">🚀 অর্ডার করুন</a>
                        </div>
                        <p style="font-size: 12px; color: #888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Restaurant call-to-action email. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 😂 Funny / Meme Style Offer
            [
                'key' => 'marketing.funny_offer',
                'name' => 'Funny Offer',
                'subject' => '😜 ক্ষুধা লাগছে? আমরা জানি!',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:#00b894; padding:30px; text-align:center; color:white;">
                        <h1>😜 ক্ষুধা লাগছে?</h1>
                    </div>
                    <div style="padding:30px;">
                        <p>হেই <strong>{{name}}</strong>,</p>
                        <p>আমরা জানি... তুমি এখন ফ্রিজ খুলে কিছু খুঁজছো 😏</p>
                        <p>কিন্তু wait! {{site_name}}-এ আছে তার থেকেও ভালো কিছু 😋</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#00b894; color:white; padding:12px 30px; border-radius:999px;">🍔 এখনই অর্ডার করো</a>
                        </div>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Funny engaging email. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 😴 Lazy Mode Trigger
            [
                'key' => 'marketing.lazy_offer',
                'name' => 'Lazy Mood Offer',
                'subject' => '😴 রান্না করতে ইচ্ছা করছে না?',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:#0984e3; padding:30px; text-align:center; color:white;">
                        <h1>😴 Lazy Mood?</h1>
                    </div>
                    <div style="padding:30px;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আজ রান্না করতে ইচ্ছা করছে না? 😅</p>
                        <p>চিন্তা নেই! আমরা আছি 💙</p>
                        <p><strong>👉 আপনি বসে থাকুন, আমরা খাবার পৌঁছে দিবো!</strong></p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#0984e3; color:white; padding:12px 30px; border-radius:999px;">🚀 অর্ডার করুন</a>
                        </div>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Lazy mood targeting. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🤤 Craving Trigger
            [
                'key' => 'marketing.craving',
                'name' => 'Craving Trigger',
                'subject' => '🤤 হঠাৎ খেতে ইচ্ছা করছে?',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:#e84393; padding:30px; text-align:center; color:white;">
                        <h1>🤤 Craving Alert!</h1>
                    </div>
                    <div style="padding:30px;">
                        <p>হ্যালো <strong>{{name}}</strong>,</p>
                        <p>হঠাৎ করে কিছু tasty খেতে ইচ্ছা করছে? 😋</p>
                        <p>👉 আমরা জানি! তাই নিয়ে এসেছি আপনার পছন্দের খাবার 🍔🍗</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#e84393; color:white; padding:12px 30px; border-radius:999px;">🍽️ অর্ডার করুন</a>
                        </div>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Food craving trigger. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🕒 Night Offer
            [
                'key' => 'marketing.night_offer',
                'name' => 'Night Offer',
                'subject' => '🌙 রাত জেগে আছেন? অফার আছে!',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:#2d3436; padding:30px; text-align:center; color:white;">
                        <h1>🌙 Night Cravings?</h1>
                    </div>
                    <div style="padding:30px;">
                        <p>হেই <strong>{{name}}</strong>,</p>
                        <p>রাতে ঘুম আসছে না? 😅</p>
                        <p>👉 কিছু tasty খাবার হলে মন্দ হয় না 😉</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#2d3436; color:white; padding:12px 30px; border-radius:999px;">🌙 এখনই অর্ডার করুন</a>
                        </div>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Night time marketing. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🧠 Emotional Re-engage
            [
                'key' => 'marketing.emotional',
                'name' => 'Emotional Connect',
                'subject' => '💔 আমাদের ভুলে গেছেন নাকি?',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:#d63031; padding:30px; text-align:center; color:white;">
                        <h1>💔 আমাদের ভুলে গেছেন?</h1>
                    </div>
                    <div style="padding:30px;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আগে আপনি প্রায়ই আসতেন... এখন আর আসেন না 😢</p>
                        <p>আমরা কিন্তু এখনো আপনার জন্য অপেক্ষা করছি ❤️</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#d63031; color:white; padding:12px 30px; border-radius:999px;">😍 আবার চলে আসুন</a>
                        </div>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Emotional re-engagement. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🛒 Cart Abandon
            [
                'key' => 'marketing.cart_reminder',
                'name' => 'Cart Reminder',
                'subject' => '🛒 আপনার কার্ট এখনো অপেক্ষায় আছে!',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:#fdcb6e; padding:30px; text-align:center;">
                        <h1>🛒 কার্ট ফেলে গেছেন!</h1>
                    </div>
                    <div style="padding:30px;">
                        <p>হ্যালো <strong>{{name}}</strong>,</p>
                        <p>আপনি কিছু আইটেম কার্টে রেখে চলে গেছেন 😢</p>
                        <p>👉 এগুলো কিন্তু এখনো আপনার জন্য অপেক্ষা করছে!</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{cart_link}}" style="background:#fdcb6e; color:black; padding:12px 30px; border-radius:999px;">🛒 কার্টে ফিরে যান</a>
                        </div>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Cart abandonment recovery. Vars: name, cart_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🎯 Personalized Offer
            [
                'key' => 'marketing.personal_offer',
                'name' => 'Personalized Offer',
                'subject' => '🎯 শুধু আপনার জন্য স্পেশাল!',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:#00cec9; padding:30px; text-align:center; color:white;">
                        <h1>🎯 Just For You!</h1>
                    </div>
                    <div style="padding:30px;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আমরা লক্ষ্য করেছি আপনি {{favorite_item}} পছন্দ করেন 😋</p>
                        <p>তাই আপনার জন্য স্পেশাল অফার 🎁</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#00cec9; color:white; padding:12px 30px; border-radius:999px;">🍽️ অর্ডার করুন</a>
                        </div>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Personalized marketing. Vars: name, favorite_item, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔁 Order Again
            [
                'key' => 'marketing.order_again',
                'name' => 'Order Again',
                'subject' => '🔁 আবার আগের অর্ডার করবেন?',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:#6c5ce7; padding:30px; text-align:center; color:white;">
                        <h1>🔁 Repeat Order?</h1>
                    </div>
                    <div style="padding:30px;">
                        <p>হ্যালো <strong>{{name}}</strong>,</p>
                        <p>আপনার শেষ অর্ডারটি ছিল: <strong>{{last_order_item}}</strong></p>
                        <p>👉 আবার একইটা অর্ডার করবেন? 😋</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#6c5ce7; color:white; padding:12px 30px; border-radius:999px;">🔁 আবার অর্ডার করুন</a>
                        </div>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Repeat order trigger. Vars: name, last_order_item, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // ⭐ Review Request
            [
                'key' => 'marketing.review',
                'name' => 'Review Request',
                'subject' => '⭐ আপনার মতামত দিন!',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:#f1c40f; padding:30px; text-align:center;">
                        <h1>⭐ Review দিন</h1>
                    </div>
                    <div style="padding:30px;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আপনার অর্ডারটি কেমন ছিল?</p>
                        <p>👉 আপনার ফিডব্যাক আমাদের জন্য অনেক গুরুত্বপূর্ণ ❤️</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{review_link}}" style="background:#f1c40f; color:black; padding:12px 30px; border-radius:999px;">⭐ রিভিউ দিন</a>
                        </div>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Review collection email. Vars: name, review_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🎁 Surprise Gift
            [
                'key' => 'marketing.surprise',
                'name' => 'Surprise Gift',
                'subject' => '🎁 আপনার জন্য একটি সারপ্রাইজ!',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:#ff7675; padding:30px; text-align:center; color:white;">
                        <h1>🎁 Surprise!</h1>
                    </div>
                    <div style="padding:30px;">
                        <p>হ্যালো <strong>{{name}}</strong>,</p>
                        <p>আজ কোনো কারণ ছাড়াই আমরা আপনাকে একটা গিফট দিচ্ছি 😍</p>
                        <div style="background:#ffeaa7; padding:15px; border-radius:10px; text-align:center;">
                            <strong>🎉 Special Discount Inside</strong>
                        </div>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#ff7675; color:white; padding:12px 30px; border-radius:999px;">🎁 ক্লেইম করুন</a>
                        </div>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Surprise engagement. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🤯 Crazy Deal
            [
                'key' => 'marketing.crazy_deal',
                'name' => 'Crazy Deal',
                'subject' => '🤯 বিশ্বাস করবেন না এই অফার!',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:#d63031; padding:30px; text-align:center; color:white;">
                        <h1>🤯 Crazy Deal!</h1>
                    </div>
                    <div style="padding:30px;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>এমন অফার আপনি আগে কখনো দেখেননি 😳</p>
                        <p><strong>👉 Limited Time Crazy Deal চলছে!</strong></p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#d63031; color:white; padding:12px 30px; border-radius:999px;">🔥 এখনই নিন</a>
                        </div>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'High urgency crazy deal. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🎂 Birthday Offer
            [
                'key' => 'marketing.birthday',
                'name' => 'Birthday Offer',
                'subject' => '🎂 শুভ জন্মদিন {{name}} — স্পেশাল গিফট আপনার জন্য!',
                'body' => '<div style="font-family: Arial; max-width: 600px; margin: auto; background: #fff; border-radius: 16px; border: 1px solid #eee;">
                    <div style="background: linear-gradient(135deg, #ff6a00, #ffcc00); padding: 30px; text-align: center;">
                        <h1>🎉 Happy Birthday {{name}}!</h1>
                    </div>
                    <div style="padding: 30px;">
                        <p>আজ আপনার জন্য রয়েছে স্পেশাল গিফট 🎁</p>
                        <div style="background: #fef3c7; padding: 15px; border-radius: 10px; text-align: center;">
                            <p><strong>20% Discount 🎂</strong></p>
                            <code>BIRTHDAY20</code>
                        </div>
                        <p style="margin-top: 20px;">আজই ব্যবহার করুন!</p>
                        <div style="text-align: center; margin: 20px 0;">
                            <a href="{{order_link}}" style="background: #ff6a00; color: white; padding: 12px 30px; border-radius: 999px; text-decoration: none;">🎁 অর্ডার করুন</a>
                        </div>
                        <p style="font-size: 12px; color: #888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Birthday special email. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // =========================================
            // 🆕 অর্ডার কনফার্মেশন টেমপ্লেট
            // =========================================

            [
                'key' => 'order.confirmation',
                'name' => 'Order Confirmation',
                'subject' => 'অর্ডার কনফার্ম — {{order_no}} — {{site_name}}',
                'body' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #eee;">
                    <div style="background: linear-gradient(135deg, #27ae60, #2ecc71); padding: 30px; text-align: center; color: white;">
                        <h1 style="margin: 0; font-size: 28px;">✅ অর্ডার কনফার্ম!</h1>
                        <p style="margin: 10px 0 0;">আপনার অর্ডারটি সফলভাবে হয়েছে</p>
                    </div>
                    <div style="padding: 30px; color: #2a1d18;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আপনার অর্ডারটি আমরা পেয়ে গেছি। খুব শীঘ্রই ডেলিভারি দেওয়া হবে।</p>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 12px; margin: 20px 0;">
                            <table style="width: 100%;">
                                <tr><td style="padding: 4px;"><strong>অর্ডার নং:</strong></td><td style="padding: 4px;">{{order_no}}</td></tr>
                                <tr><td style="padding: 4px;"><strong>মোট মূল্য:</strong></td><td style="padding: 4px;">৳{{total}}</td></tr>
                                <tr><td style="padding: 4px;"><strong>ডেলিভারি সময়:</strong></td><td style="padding: 4px;">{{delivery_time}}</td></tr>
                            </table>
                        </div>
                        <div style="text-align: center; margin: 25px 0;">
                            <a href="{{order_link}}" style="background: #27ae60; color: white; padding: 12px 35px; border-radius: 999px; text-decoration: none; font-weight: bold;">📦 অর্ডার ট্র্যাক করুন</a>
                        </div>
                        <p style="font-size: 12px; color: #888;">— {{site_name}} টিম</p>
                    </div>
                </div>',
                'description' => 'অর্ডার করার পর স্বয়ংক্রিয়ভাবে পাঠানো হবে',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🎉 ওয়েলকাম ইমেইল
            [
                'key' => 'welcome.email',
                'name' => 'Welcome Email',
                'subject' => 'স্বাগতম {{name}}! — {{site_name}}',
                'body' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #eee;">
                    <div style="background: linear-gradient(135deg, #c0392b, #e8671a); padding: 40px 30px; text-align: center; color: white;">
                        <h1 style="margin: 0;">🎉 স্বাগতম, {{name}}!</h1>
                        <p style="margin: 10px 0 0;">আপনি এখন {{site_name}} পরিবারের সদস্য</p>
                    </div>
                    <div style="padding: 30px;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আপনার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে।</p>
                        <div style="background: #fef3c7; padding: 15px; border-radius: 10px; margin: 20px 0; text-align: center;">
                            <p><strong>🎁 প্রথম অর্ডার অফার!</strong></p>
                            <p>আপনার প্রথম অর্ডারে <strong>১০% ছাড়</strong></p>
                            <code style="background: #c0392b; color: white; padding: 6px 12px; border-radius: 6px;">WELCOME10</code>
                        </div>
                        <div style="text-align: center; margin: 20px 0;">
                            <a href="{{order_link}}" style="background: #c0392b; color: white; padding: 12px 30px; border-radius: 999px; text-decoration: none;">🍽️ অর্ডার করুন</a>
                        </div>
                        <p style="font-size: 12px; color: #888;">— {{site_name}} টিম</p>
                    </div>
                </div>',
                'description' => 'নতুন ইউজার রেজিস্ট্রেশন করার পর পাঠানো হবে',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔐 পাসওয়ার্ড রিসেট
            [
                'key' => 'password.reset',
                'name' => 'Password Reset',
                'subject' => 'পাসওয়ার্ড রিসেট করুন — {{site_name}}',
                'body' => '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background: #fff; border-radius: 16px; border: 1px solid #eee;">
                    <div style="background: #c0392b; padding: 25px; text-align: center; color: white;">
                        <h1 style="margin: 0;">🔐 পাসওয়ার্ড রিসেট</h1>
                    </div>
                    <div style="padding: 30px;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আমরা আপনার অ্যাকাউন্টের জন্য একটি পাসওয়ার্ড রিসেট রিকোয়েস্ট পেয়েছি।</p>
                        <div style="text-align: center; margin: 25px 0;">
                            <a href="{{reset_link}}" style="background: #c0392b; color: white; padding: 12px 35px; border-radius: 999px; text-decoration: none;">🔑 পাসওয়ার্ড রিসেট করুন</a>
                        </div>
                        <p>যদি আপনি এই রিকোয়েস্টটি করেননি, তাহলে এই ইমেইলটি উপেক্ষা করুন।</p>
                        <p style="font-size: 12px; color: #888; margin-top: 25px;">— {{site_name}} সাপোর্ট টিম</p>
                    </div>
                </div>',
                'description' => 'পাসওয়ার্ড রিসেট ইমেইল',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🐔 Roast-Style Funny
            [
                'key' => 'marketing.roast',
                'name' => 'Roast Style (ভাই তুমি কী করো)',
                'subject' => '😂 ভাই, তুমি কি ঘুমাচ্ছো নাকি খাবার ভুলে গেছো?',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:linear-gradient(135deg,#f953c6,#b91d73); padding:30px; text-align:center; color:white;">
                        <h1 style="margin:0; font-size:28px;">😂 ভাই কী অবস্থা?</h1>
                        <p style="margin:8px 0 0; font-size:14px;">সিরিয়াসলি জিজ্ঞেস করছি...</p>
                    </div>
                    <div style="padding:30px;">
                        <p>হেই <strong>{{name}}</strong>,</p>
                        <p>তোমাকে নিয়ে আমরা একটু চিন্তিত 🤔</p>
                        <p>তুমি কি জানো পেট ভরা থাকলে মেজাজও ভালো থাকে? বিজ্ঞান বলে 😎</p>
                        <div style="background:#fff0f6; padding:15px; border-radius:12px; margin:20px 0; text-align:center;">
                            <p style="margin:0; font-size:18px;">🧠 <strong>আমাদের রিসার্চ বলছে:</strong></p>
                            <p style="margin:8px 0 0; color:#b91d73;">খালি পেটে ভালো সিদ্ধান্ত নেওয়া যায় না 😅</p>
                        </div>
                        <p>তাই ভাই, এখনই একটা অর্ডার দাও — <strong>{{site_name}}</strong> তোমার পাশে আছে 💪</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:linear-gradient(135deg,#f953c6,#b91d73); color:white; padding:14px 35px; border-radius:999px; text-decoration:none; font-weight:bold; font-size:15px;">🍔 ঠিকাছে, খামু!</a>
                        </div>
                        <p style="font-size:12px; color:#888; text-align:center;">কোনো pressure নেই... তবে পেট চাইছে 😬</p>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Funny roast-style re-engagement. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🕵️ Detective Style
            [
                'key' => 'marketing.detective',
                'name' => 'Detective Mode (গোয়েন্দা)',
                'subject' => '🕵️ গোপন তথ্য ফাঁস! আপনার পেট চাইছে...',
                'body' => '<div style="font-family: Georgia, serif; max-width:600px; margin:auto; background:#1a1a2e; border-radius:16px; border:2px solid #e94560; color:white;">
                    <div style="padding:30px; text-align:center; border-bottom:2px dashed #e94560;">
                        <p style="color:#e94560; font-size:12px; letter-spacing:3px; margin:0;">⚠️ TOP SECRET ⚠️</p>
                        <h1 style="margin:10px 0; font-size:26px;">🕵️ গোয়েন্দা রিপোর্ট</h1>
                        <p style="color:#aaa; font-size:13px; margin:0;">Classification: HUNGRY</p>
                    </div>
                    <div style="padding:30px;">
                        <p style="color:#e94560;">প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আমাদের গোয়েন্দা দল জানাচ্ছে যে —</p>
                        <div style="background:#16213e; padding:15px; border-radius:10px; border-left:4px solid #e94560; margin:20px 0;">
                            <p style="margin:0; font-family:monospace; font-size:13px; color:#0f3460;">
                                > Subject: {{name}}<br>
                                > Status: ক্ষুধার্ত (৯৫% নিশ্চিত)<br>
                                > Last meal: অনেক আগে<br>
                                > Recommended action: <span style="color:#e94560;">ORDER NOW</span>
                            </p>
                        </div>
                        <p>মিশন সফল করতে নিচের বাটনে ক্লিক করুন 👇</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#e94560; color:white; padding:14px 35px; border-radius:999px; text-decoration:none; font-weight:bold;">🕵️ মিশন শুরু করুন</a>
                        </div>
                        <p style="font-size:11px; color:#555; text-align:center;">এই মেসেজ পড়ার ৩০ মিনিটের মধ্যে নষ্ট হয়ে যাবে 😅</p>
                        <p style="font-size:12px; color:#555;">— {{site_name}} Secret Division 🕵️</p>
                    </div>
                </div>',
                'description' => 'Funny spy/detective themed email. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 📺 Drama Queen Style
            [
                'key' => 'marketing.drama',
                'name' => 'Drama Queen (নাটকীয়)',
                'subject' => '😭 আপনি না আসলে আমরা কাঁদবো... সত্যি বলছি!',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:linear-gradient(135deg,#667eea,#764ba2); padding:30px; text-align:center; color:white;">
                        <h1 style="margin:0; font-size:28px;">😭 আমরা কাঁদছি...</h1>
                        <p style="margin:8px 0 0;">(নাটকীয়ভাবে)</p>
                    </div>
                    <div style="padding:30px;">
                        <p>প্রিয় <strong>{{name}}</strong>,</p>
                        <p>আপনি কি জানেন রান্নাঘরের বাবুর্চি কতটা কষ্ট করেন? 😢</p>
                        <p>সকাল থেকে মসলা বাটেন, চুলার আঁচে রান্না করেন — শুধু আপনার জন্য!</p>
                        <div style="background:#f3e5f5; padding:20px; border-radius:12px; margin:20px 0; text-align:center;">
                            <p style="font-size:30px; margin:0;">👨‍🍳</p>
                            <p style="color:#764ba2; font-style:italic; margin:10px 0 0;">"আজকে কেউ আসবে না... 😔"<br><small>— বাবুর্চি ভাই, {{site_name}}</small></p>
                        </div>
                        <p>আপনি না আসলে বাবুর্চি ভাই আজ ঘুমাতে পারবেন না! 😭</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:linear-gradient(135deg,#667eea,#764ba2); color:white; padding:14px 35px; border-radius:999px; text-decoration:none; font-weight:bold;">🍽️ বাবুর্চি ভাইকে বাঁচান!</a>
                        </div>
                        <p style="font-size:11px; color:#888; text-align:center;">*এটি সম্পূর্ণ কাল্পনিক কিন্তু খাবার real 😄</p>
                        <p style="font-size:12px; color:#888;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'Over-dramatic funny email. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🎮 Gamer Style
            [
                'key' => 'marketing.gamer',
                'name' => 'Gamer Mode (গেমার)',
                'subject' => '🎮 LEVEL UP! খাবার collect করুন আগেই শেষ হওয়ার আগে!',
                'body' => '<div style="font-family: \'Courier New\', monospace; max-width:600px; margin:auto; background:#0a0a0a; border-radius:16px; border:2px solid #00ff41; color:#00ff41;">
                    <div style="padding:25px; text-align:center; border-bottom:2px solid #00ff41;">
                        <p style="font-size:11px; letter-spacing:2px; margin:0; color:#888;">PLAYER: {{name}}</p>
                        <h1 style="margin:10px 0; font-size:28px; text-shadow:0 0 20px #00ff41;">🎮 HUNGER LEVEL: MAX</h1>
                        <div style="background:#001a00; padding:8px; border-radius:8px; display:inline-block; font-size:13px;">
                            ❤️❤️❤️ HP: 30/100 (ক্ষুধার কারণে কমছে!)
                        </div>
                    </div>
                    <div style="padding:30px;">
                        <p style="color:#00ff41;">⚡ QUEST: খাবার সংগ্রহ করুন!</p>
                        <div style="background:#001a00; padding:15px; border-radius:10px; margin:15px 0; border:1px solid #003300;">
                            <p style="margin:0; font-size:13px;">
                                📦 AVAILABLE ITEMS:<br>
                                ├── 🍔 Burger Combo [+50 HP]<br>
                                ├── 🍗 Chicken Pack [+40 HP]<br>
                                └── 🥤 Mega Drink [+20 HP]
                            </p>
                        </div>
                        <p style="color:#ffff00;">⚠️ WARNING: HP ০ হলে game over (ক্ষুধায় মরবেন 😅)</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#00ff41; color:#0a0a0a; padding:14px 35px; border-radius:8px; text-decoration:none; font-weight:bold; font-family:monospace; display:inline-block;">
                                [ PRESS ENTER TO ORDER ] 🎮
                            </a>
                        </div>
                        <p style="font-size:11px; color:#555; text-align:center;">Insert coin to continue... 😄</p>
                        <p style="font-size:12px; color:#555;">— {{site_name}} Gaming Division</p>
                    </div>
                </div>',
                'description' => 'Gamer themed funny email. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🧪 Scientist Style
            [
                'key' => 'marketing.scientist',
                'name' => 'Scientist Mode (বিজ্ঞানী)',
                'subject' => '🔬 বৈজ্ঞানিক প্রমাণ: আপনার এখনই খাওয়া দরকার!',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
                    <div style="background:linear-gradient(135deg,#11998e,#38ef7d); padding:30px; text-align:center; color:white;">
                        <h1 style="margin:0;">🔬 Scientific Report</h1>
                        <p style="margin:8px 0 0; font-size:13px;">ChillGhor Food Research Institute</p>
                    </div>
                    <div style="padding:30px;">
                        <p>প্রিয় গবেষণা সাবজেক্ট <strong>{{name}}</strong>,</p>
                        <p>আমাদের উন্নত প্রযুক্তি বিশ্লেষণ করে দেখেছে:</p>
                        <div style="background:#f0fff4; padding:15px; border-radius:10px; border:1px solid #38ef7d; margin:15px 0;">
                            <table style="width:100%; font-size:13px; border-collapse:collapse;">
                                <tr style="border-bottom:1px solid #c6f6d5;"><td style="padding:8px;">🍔 খাওয়ার ইচ্ছা</td><td style="padding:8px; text-align:right; color:#11998e;"><strong>99.9%</strong></td></tr>
                                <tr style="border-bottom:1px solid #c6f6d5;"><td style="padding:8px;">😅 রান্না করার ইচ্ছা</td><td style="padding:8px; text-align:right; color:#e53e3e;"><strong>2.3%</strong></td></tr>
                                <tr><td style="padding:8px;">🚀 অর্ডার করার সম্ভাবনা</td><td style="padding:8px; text-align:right; color:#11998e;"><strong>95%</strong></td></tr>
                            </table>
                        </div>
                        <p><strong>উপসংহার:</strong> আপনাকে এখনই অর্ডার করা উচিত (p &lt; 0.001) 😄</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:linear-gradient(135deg,#11998e,#38ef7d); color:white; padding:14px 35px; border-radius:999px; text-decoration:none; font-weight:bold;">🔬 হাইপোথিসিস প্রমাণ করুন!</a>
                        </div>
                        <p style="font-size:11px; color:#888; text-align:center;">*এই গবেষণা ১০০% বৈজ্ঞানিক নয়, কিন্তু খাবার ১০০% real 😎</p>
                        <p style="font-size:12px; color:#888;">— Dr. {{site_name}}, PhD in Deliciousness</p>
                    </div>
                </div>',
                'description' => 'Funny scientist themed email. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🤖 Robot/AI Style
            [
                'key' => 'marketing.robot',
                'name' => 'Robot AI Mode (রোবট)',
                'subject' => '🤖 ERROR: আপনার পেট খালি! System alert!',
                'body' => '<div style="font-family: \'Courier New\', monospace; max-width:600px; margin:auto; background:#f8f9fa; border-radius:16px; border:2px solid #343a40;">
                    <div style="background:#343a40; padding:25px; text-align:center; color:#00d2ff;">
                        <p style="font-size:11px; letter-spacing:2px; margin:0; color:#aaa;">CHILLGHOR AI SYSTEM v2.0</p>
                        <h1 style="margin:10px 0; font-size:26px;">🤖 ALERT DETECTED</h1>
                        <div style="background:#ff000022; border:1px solid red; padding:8px; border-radius:4px; color:#ff6b6b; font-size:12px;">
                            ⚠️ HUNGER_LEVEL = CRITICAL
                        </div>
                    </div>
                    <div style="padding:30px; color:#343a40;">
                        <p>GREETING, HUMAN <strong>{{name}}</strong>.</p>
                        <p>MY SENSORS DETECT THE FOLLOWING:</p>
                        <div style="background:#343a40; padding:15px; border-radius:8px; color:#00d2ff; font-size:13px; margin:15px 0;">
                            &gt; SCANNING...<br>
                            &gt; HUNGER_STATUS: VERY_HIGH<br>
                            &gt; LAST_ORDER: TOO_LONG_AGO<br>
                            &gt; RECOMMENDATION: ORDER_IMMEDIATELY<br>
                            &gt; CHILLGHOR_URL: LOADED ✓
                        </div>
                        <p>EXECUTING OPTIMAL SOLUTION... 🤖</p>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#343a40; color:#00d2ff; padding:14px 35px; border-radius:8px; text-decoration:none; font-weight:bold; font-family:monospace; display:inline-block; border:1px solid #00d2ff;">
                                [EXECUTE: ORDER_FOOD]
                            </a>
                        </div>
                        <p style="font-size:11px; color:#888; text-align:center;">THIS MESSAGE WAS SENT WITH LOVE (EMOTION MODULE: ACTIVE 💙)</p>
                        <p style="font-size:12px; color:#888;">— {{site_name}} AI Department 🤖</p>
                    </div>
                </div>',
                'description' => 'Funny robot/AI themed email. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 📱 WhatsApp Fake Style
            [
                'key' => 'marketing.whatsapp_style',
                'name' => 'WhatsApp Style Message',
                'subject' => '💬 [ChillGhor] নতুন মেসেজ এসেছে...',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#e5ddd5; border-radius:16px; overflow:hidden;">
                    <div style="background:#075e54; padding:15px 20px; display:flex; align-items:center;">
                        <div style="width:40px; height:40px; background:#25d366; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px;">🍔</div>
                        <div style="margin-left:12px; color:white;">
                            <div style="font-weight:bold; font-size:15px;">ChillGhor Official</div>
                            <div style="font-size:12px; opacity:0.8;">online</div>
                        </div>
                    </div>
                    <div style="padding:20px;">
                        <div style="background:white; padding:12px 15px; border-radius:0 12px 12px 12px; max-width:85%; box-shadow:0 1px 3px rgba(0,0,0,0.1); margin-bottom:8px;">
                            <p style="margin:0; font-size:14px;">হ্যালো <strong>{{name}}</strong>! 👋</p>
                            <p style="font-size:10px; color:#aaa; margin:4px 0 0; text-align:right;">10:30 AM ✓✓</p>
                        </div>
                        <div style="background:white; padding:12px 15px; border-radius:0 12px 12px 12px; max-width:85%; box-shadow:0 1px 3px rgba(0,0,0,0.1); margin-bottom:8px;">
                            <p style="margin:0; font-size:14px;">আজকে কিন্তু দারুণ অফার আছে 😋🔥</p>
                            <p style="font-size:10px; color:#aaa; margin:4px 0 0; text-align:right;">10:30 AM ✓✓</p>
                        </div>
                        <div style="background:white; padding:12px 15px; border-radius:0 12px 12px 12px; max-width:85%; box-shadow:0 1px 3px rgba(0,0,0,0.1); margin-bottom:20px;">
                            <p style="margin:0; font-size:14px;">এখনই অর্ডার করলে বিশেষ ডিসকাউন্ট পাবেন! 👇</p>
                            <p style="font-size:10px; color:#aaa; margin:4px 0 0; text-align:right;">10:31 AM ✓✓</p>
                        </div>
                        <div style="text-align:center;">
                            <a href="{{order_link}}" style="background:#25d366; color:white; padding:12px 30px; border-radius:999px; text-decoration:none; font-weight:bold; display:inline-block;">📲 অর্ডার করুন</a>
                        </div>
                        <p style="font-size:11px; color:#888; text-align:center; margin-top:15px;">— {{site_name}}</p>
                    </div>
                </div>',
                'description' => 'WhatsApp style funny email. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 📰 Breaking News Style
            [
                'key' => 'marketing.breaking_news',
                'name' => 'Breaking News Style',
                'subject' => '📺 BREAKING: বিশাল খাবার অফার আবিষ্কার — দেশ স্তব্ধ!',
                'body' => '<div style="font-family: Georgia, serif; max-width:600px; margin:auto; background:#fff; border-radius:4px; border:2px solid #cc0000;">
                    <div style="background:#cc0000; padding:12px 20px; display:flex; align-items:center; justify-content:space-between;">
                        <div style="color:white; font-weight:bold; font-size:16px;">📺 CHILLGHOR NEWS</div>
                        <div style="color:#ffcc00; font-size:12px; font-family:Arial;">LIVE 🔴</div>
                    </div>
                    <div style="background:#cc0000; padding:8px 20px;">
                        <div style="color:white; font-size:12px; font-family:Arial; white-space:nowrap; overflow:hidden;">
                            🚨 BREAKING &nbsp;&nbsp; খাবার অফার চলছে &nbsp;&nbsp; 🔥 সীমিত সময় &nbsp;&nbsp; ⚡ মিস করবেন না &nbsp;&nbsp; 🚨 BREAKING
                        </div>
                    </div>
                    <div style="padding:25px;">
                        <div style="font-size:11px; color:#888; font-family:Arial; margin-bottom:8px;">আজ, এইমাত্র | সংবাদদাতা: ChillGhor বার্তাকক্ষ</div>
                        <h2 style="font-size:22px; margin:0 0 15px; color:#cc0000; line-height:1.3;">বিশাল খাবার অফার ঘোষণা: দেশের মানুষ অবাক!</h2>
                        <p>প্রিয় পাঠক <strong>{{name}}</strong>,</p>
                        <p>আজ বিকেলে ChillGhor কর্তৃপক্ষ একটি অভূতপূর্ব খাবার অফার ঘোষণা করেছে যা দেশের সকল ক্ষুধার্ত মানুষকে হতবাক করে দিয়েছে।</p>
                        <p>বিশেষজ্ঞরা জানাচ্ছেন, এমন অফার আগে কখনো আসেনি এবং এটি কতক্ষণ থাকবে তা নিশ্চিত নয়। 😱</p>
                        <div style="background:#fff3f3; padding:15px; border-left:4px solid #cc0000; margin:20px 0;">
                            <p style="margin:0; font-style:italic; color:#555;">"আমি ৩০ বছর ধরে সংবাদ করি, এমন অফার দেখিনি।"<br><small>— কাল্পনিক বিশেষজ্ঞ</small></p>
                        </div>
                        <div style="text-align:center; margin:25px 0;">
                            <a href="{{order_link}}" style="background:#cc0000; color:white; padding:14px 35px; border-radius:4px; text-decoration:none; font-weight:bold; font-family:Arial;">📺 সর্বশেষ অফার দেখুন</a>
                        </div>
                        <p style="font-size:11px; color:#888; text-align:center;">*এটি একটি বিনোদনমূলক ইমেইল 😄</p>
                        <p style="font-size:12px; color:#888; font-family:Arial;">— {{site_name}} News Desk 📺</p>
                    </div>
                </div>',
                'description' => 'Breaking news funny style. Vars: name, order_link, site_name',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        foreach ($templates as $template) {
            DB::table('email_templates')->updateOrInsert(
                ['key' => $template['key']],
                $template
            );
        }

        $this->command->info('✅ ' . count($templates) . ' টি ইমেইল টেমপ্লেট সফলভাবে সিড করা হয়েছে!');
    }
}
