<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = ['key', 'name', 'subject', 'body', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public static function render(string $key, array $vars = []): ?array
    {
        $tpl = self::where('key', $key)->where('is_active', true)->first();
        if (!$tpl) return null;
        $vars = array_merge(['site_name' => Setting::get('site_name', 'Chill Ghor')], $vars);
        $sub = $tpl->subject;
        $body = $tpl->body;
        foreach ($vars as $k => $v) {
            $sub = str_replace('{{' . $k . '}}', (string) $v, $sub);
            $body = str_replace('{{' . $k . '}}', (string) $v, $body);
        }
        return ['subject' => $sub, 'body' => $body, 'template_id' => $tpl->id];
    }
}
