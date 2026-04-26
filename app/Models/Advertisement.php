<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Advertisement extends Model
{
    protected $fillable = [
        'title', 'body', 'emoji', 'badge',
        'bg_color', 'text_color', 'cta_text', 'cta_url', 'cta_color',
        'style', 'show_on_pages', 'is_active',
        'starts_at', 'ends_at', 'sort_order',
    ];

    protected $casts = [
        'show_on_pages' => 'array',
        'is_active'     => 'boolean',
        'starts_at'     => 'datetime',
        'ends_at'       => 'datetime',
    ];

    /**
     * Active ads for a specific page (home, menu, all)
     */
    public static function forPage(string $page): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->orderBy('sort_order')
            ->get()
            ->filter(function ($ad) use ($page) {
                $pages = $ad->show_on_pages ?? ['all'];
                return in_array('all', $pages) || in_array($page, $pages);
            });
    }

    public function isExpired(): bool
    {
        return $this->ends_at && $this->ends_at->isPast();
    }

    public function isScheduled(): bool
    {
        return $this->starts_at && $this->starts_at->isFuture();
    }

    public function statusLabel(): string
    {
        if (!$this->is_active) return '❌ নিষ্ক্রিয়';
        if ($this->isExpired()) return '⏰ মেয়াদ শেষ';
        if ($this->isScheduled()) return '🕐 নির্ধারিত';
        return '✅ সক্রিয়';
    }
}