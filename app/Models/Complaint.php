<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class Complaint extends Model
{
    protected $fillable = [
        'complainant_name',
        'contact',
        'statement',
        'status',
        'assigned_officer_id',
        'crime_record_id',
    ];

    // ─────────────────── Encryption: contact field ───────────────────

    /**
     * Automatically encrypt the contact value before saving.
     */
    public function setContactAttribute(string $value): void
    {
        $this->attributes['contact'] = encrypt($value);
    }

    /**
     * Automatically decrypt the contact value when reading.
     * Returns '[decryption failed]' if decryption fails (e.g. key mismatch).
     */
    public function getContactAttribute(string $value): string
    {
        try {
            return decrypt($value);
        } catch (\Exception $e) {
            return '[decryption failed]';
        }
    }

    // ─────────────────── Relationships ───────────────────

    /**
     * The officer assigned to handle this complaint.
     */
    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_officer_id');
    }

    /**
     * The crime record this complaint is linked to.
     */
    public function caseRecord(): BelongsTo
    {
        return $this->belongsTo(CrimeRecord::class, 'crime_record_id');
    }
}
