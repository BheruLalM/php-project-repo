<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'subject_id' => 'integer',
    ];

    // ─────────────────── Relationships ───────────────────

    /**
     * The user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
