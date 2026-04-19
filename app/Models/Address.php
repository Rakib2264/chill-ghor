<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'label', 'recipient_name', 'phone',
        'area', 'address_line', 'is_default',
    ];

    protected $casts = ['is_default' => 'boolean'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function getFullAttribute(): string {
        return trim(($this->area ? $this->area . ', ' : '') . $this->address_line);
    }
}
