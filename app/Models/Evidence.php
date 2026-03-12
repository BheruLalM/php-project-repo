<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Evidence extends Model
{
    protected $table = 'evidences';

    protected $fillable = [
        'crime_record_id',
        'file_path',
        'file_type',
        'original_name',
        'description',
        'uploaded_by',
    ];

    // ─────────────────── Accessors ───────────────────

    /**
     * Return a public URL for the evidence file.
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    // ─────────────────── Relationships ───────────────────

    /**
     * The crime record this evidence belongs to.
     */
    public function crimeRecord(): BelongsTo
    {
        return $this->belongsTo(CrimeRecord::class);
    }

    /**
     * The officer who uploaded this evidence.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
