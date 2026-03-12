<?php

namespace App\Traits;

use App\Models\AuditLog;

trait LogsActivity
{
    /**
     * Auto-log when the model is updated.
     */
    protected static function bootLogsActivity(): void
    {
        static::updated(function ($model) {
            static::createLog('edited', $model);
        });
    }

    /**
     * Manual log method — call anywhere.
     */
    public static function createLog(string $action, $subject, ?string $description = null): void
    {
        if (!auth()->check()) {
            return;
        }

        AuditLog::create([
            'user_id'      => auth()->id(),
            'action'       => $action,
            'subject_type' => class_basename($subject),
            'subject_id'   => $subject->id,
            'description'  => $description ?? auth()->user()->name . " {$action} " . class_basename($subject) . " #{$subject->id}",
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
        ]);
    }
}
