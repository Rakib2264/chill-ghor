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
                            <a href="{{order_link}}" style="background: #c0392b; color: white; padding: 12px 30px; border-radius: 999px; text-decoration: none; font-weight: bold;">
                                🍔 এখনই অর্ডার করুন
                            </a>
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
                            <a href="{{order_link}}" style="background: #27ae60; color: white; padding: 12px 30px; border-radius: 999px; text-decoration: none;">
                                🚀 অর্ডার করুন
                            </a>
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
                <a href="{{order_link}}" style="background:#00b894; color:white; padding:12px 30px; border-radius:999px;">
                    🍔 এখনই অর্ডার করো
                </a>
            </div>

            <p style="font-size:12px; color:#888;">— {{site_name}}</p>
        </div>
    </div>',
                'description' => 'Funny engaging email',
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
                <a href="{{order_link}}" style="background:#0984e3; color:white; padding:12px 30px; border-radius:999px;">
                    🚀 অর্ডার করুন
                </a>
            </div>

            <p style="font-size:12px; color:#888;">— {{site_name}}</p>
        </div>
    </div>',
                'description' => 'Lazy mood targeting',
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

            <p>👉 আমরা জানি! তাই নিয়ে এসেছি আপনার পছন্দের খাবার 🍔🍗</p>

            <div style="text-align:center; margin:25px 0;">
                <a href="{{order_link}}" style="background:#e84393; color:white; padding:12px 30px; border-radius:999px;">
                    🍽️ অর্ডার করুন
                </a>
            </div>

            <p style="font-size:12px; color:#888;">— {{site_name}}</p>
        </div>
    </div>',
                'description' => 'Food craving trigger',
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

            <p>👉 কিছু tasty খাবার হলে মন্দ হয় না 😉</p>

            <div style="text-align:center; margin:25px 0;">
                <a href="{{order_link}}" style="background:#2d3436; color:white; padding:12px 30px; border-radius:999px;">
                    🌙 এখনই অর্ডার করুন
                </a>
            </div>

            <p style="font-size:12px; color:#888;">— {{site_name}}</p>
        </div>
    </div>',
                'description' => 'Night time marketing',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🧠 Smart Emotional (Relationship Style 😄)
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

            <p>আগে আপনি প্রায়ই আসতেন... এখন আর আসেন না 😢</p>

            <p>আমরা কিন্তু এখনো আপনার জন্য অপেক্ষা করছি ❤️</p>

            <div style="text-align:center; margin:25px 0;">
                <a href="{{order_link}}" style="background:#d63031; color:white; padding:12px 30px; border-radius:999px;">
                    😍 আবার চলে আসুন
                </a>
            </div>

            <p style="font-size:12px; color:#888;">— {{site_name}}</p>
        </div>
    </div>',
                'description' => 'Emotional re-engagement',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 🛒 Cart Abandon (Very Powerful)
            [
                'key' => 'marketing.cart_reminder',
                'name' => 'Cart Reminder',
                'subject' => '🛒 আপনার কার্ট এখনো অপেক্ষায় আছে!',
                'body' => '<div style="font-family: Arial; max-width:600px; margin:auto; background:#fff; border-radius:16px; border:1px solid #eee;">
        
        <div style="background:#fdcb6e; padding:30px; text-align:center;">
            <h1>🛒 কার্ট ফেলে গেছেন!</h1>
        </div>

        <div style="padding:30px;">
            <p>হ্যালো <strong>{{name}}</strong>,</p>

            <p>আপনি কিছু আইটেম কার্টে রেখে চলে গেছেন 😢</p>

            <p>👉 এগুলো কিন্তু এখনো আপনার জন্য অপেক্ষা করছে!</p>

            <div style="text-align:center; margin:25px 0;">
                <a href="{{cart_link}}" style="background:#fdcb6e; color:black; padding:12px 30px; border-radius:999px;">
                    🛒 কার্টে ফিরে যান
                </a>
            </div>

            <p style="font-size:12px; color:#888;">— {{site_name}}</p>
        </div>
    </div>',
                'description' => 'Cart abandonment recovery',
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
                <a href="{{order_link}}" style="background:#00cec9; color:white; padding:12px 30px; border-radius:999px;">
                    🍽️ অর্ডার করুন
                </a>
            </div>

            <p style="font-size:12px; color:#888;">— {{site_name}}</p>
        </div>
    </div>',
                'description' => 'Personalized marketing',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🧾 Order Again
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
                <a href="{{order_link}}" style="background:#6c5ce7; color:white; padding:12px 30px; border-radius:999px;">
                    🔁 আবার অর্ডার করুন
                </a>
            </div>

            <p style="font-size:12px; color:#888;">— {{site_name}}</p>
        </div>
    </div>',
                'description' => 'Repeat order trigger',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 😍 Review Request
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
                <a href="{{review_link}}" style="background:#f1c40f; color:black; padding:12px 30px; border-radius:999px;">
                    ⭐ রিভিউ দিন
                </a>
            </div>

            <p style="font-size:12px; color:#888;">— {{site_name}}</p>
        </div>
    </div>',
                'description' => 'Review collection email',
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

            <p>আজ কোনো কারণ ছাড়াই আমরা আপনাকে একটা গিফট দিচ্ছি 😍</p>

            <div style="background:#ffeaa7; padding:15px; border-radius:10px; text-align:center;">
                <strong>🎉 Special Discount Inside</strong>
            </div>

            <div style="text-align:center; margin:25px 0;">
                <a href="{{order_link}}" style="background:#ff7675; color:white; padding:12px 30px; border-radius:999px;">
                    🎁 ক্লেইম করুন
                </a>
            </div>

            <p style="font-size:12px; color:#888;">— {{site_name}}</p>
        </div>
    </div>',
                'description' => 'Surprise engagement',
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
                <a href="{{order_link}}" style="background:#d63031; color:white; padding:12px 30px; border-radius:999px;">
                    🔥 এখনই নিন
                </a>
            </div>

            <p style="font-size:12px; color:#888;">— {{site_name}}</p>
        </div>
    </div>',
                'description' => 'High urgency crazy deal',
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
                        <p>আজ আপনার জন্য রয়েছে স্পেশাল গিফট 🎁</p>

                        <div style="background: #fef3c7; padding: 15px; border-radius: 10px; text-align: center;">
                            <p><strong>20% Discount 🎂</strong></p>
                            <code>BIRTHDAY20</code>
                        </div>

                        <p style="margin-top: 20px;">আজই ব্যবহার করুন!</p>

                        <p style="font-size: 12px; color: #888;">— {{site_name}}</p>
                    </div>

                </div>',
                'description' => 'Birthday special email',
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
    }
}
