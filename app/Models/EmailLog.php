<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $fillable = [
        'email_template_id', 'recipient_email', 'recipient_name', 'subject',
        'audience', 'status', 'error_message', 'sent_by', 'sent_at'
    ];
    protected $casts = ['sent_at' => 'datetime'];

    public function template(): BelongsTo { return $this->belongsTo(EmailTemplate::class, 'email_template_id'); }
    public function sender(): BelongsTo { return $this->belongsTo(User::class, 'sent_by'); }
}
