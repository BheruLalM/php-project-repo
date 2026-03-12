<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use App\Traits\LogsActivity;

class Criminal extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'full_name',
        'alias',
        'date_of_birth',
        'physical_markers',
        'photo_path',
        'nationality',
        'address',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // ─────────────────── Relationships ───────────────────

    /**
     * A criminal can be linked to many crime records.
     */
    public function crimeRecords(): HasMany
    {
        return $this->hasMany(CrimeRecord::class);
    }

    // ─────────────────── Accessors ───────────────────

    /**
     * Return a public URL for the criminal's photo,
     * or a placeholder if no photo is stored.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo_path && Storage::disk('public')->exists($this->photo_path)) {
            return Storage::url($this->photo_path);
        }

        return asset('images/placeholder-mugshot.png');
    }

    // ─────────────────── Scopes ───────────────────

    /**
     * Scope: full-text search by name or alias.
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'LIKE', "%{$term}%")
              ->orWhere('alias', 'LIKE', "%{$term}%");
        });
    }
}
