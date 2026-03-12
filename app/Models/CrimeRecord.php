<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\LogsActivity;

class CrimeRecord extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'case_number',
        'crime_type',
        'description',
        'location',
        'date_of_occurrence',
        'status',
        'assigned_officer_id',
        'criminal_id',
        'archived_at',
    ];

    protected $casts = [
        'date_of_occurrence' => 'date',
        'archived_at'        => 'datetime',
    ];

    // ─────────────────── Relationships ───────────────────

    /**
     * The criminal linked to this crime record.
     */
    public function criminal(): BelongsTo
    {
        return $this->belongsTo(Criminal::class);
    }

    /**
     * The officer assigned to this case.
     */
    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_officer_id');
    }

    /**
     * Complaints related to this crime record.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Evidence files attached to this crime record.
     */
    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }

    // ─────────────────── Scopes ───────────────────

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope: search by case number, crime type, or location.
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('case_number', 'LIKE', "%{$term}%")
              ->orWhere('crime_type', 'LIKE', "%{$term}%")
              ->orWhere('location', 'LIKE', "%{$term}%");
        });
    }
}
