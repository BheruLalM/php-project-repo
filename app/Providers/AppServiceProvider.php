<?php

namespace App\Providers;

use App\Models\Complaint;
use App\Models\CrimeRecord;
use App\Models\Criminal;
use App\Policies\ComplaintPolicy;
use App\Policies\CrimeRecordPolicy;
use App\Policies\CriminalPolicy;
use App\Services\CaseReportingService;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * Bind services as singletons.
     */
    public function register(): void
    {
        // Singleton: one instance of CaseReportingService per request lifecycle
        $this->app->singleton(CaseReportingService::class);

        // Singleton: FileUploadService (stateless utility — singleton is fine)
        $this->app->singleton(FileUploadService::class);
    }

    /**
     * Bootstrap any application services.
     * Register policies (Policy discovery alternative).
     */
    public function boot(): void
    {
        // ── Policy Registration ──────────────────────────────────────────
        Gate::policy(Criminal::class,    CriminalPolicy::class);
        Gate::policy(CrimeRecord::class, CrimeRecordPolicy::class);
        Gate::policy(Complaint::class,   ComplaintPolicy::class);
    }
}
