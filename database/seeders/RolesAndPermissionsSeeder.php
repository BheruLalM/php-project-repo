<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Define all permissions ──────────────────────────────────────
        $permissions = [
            // Officers management (admin only)
            'manage-officers',

            // Criminals
            'view-criminals',
            'create-criminals',
            'edit-criminals',
            'delete-criminals',      // admin only

            // Cases
            'view-cases',
            'create-cases',
            'edit-cases',
            'delete-cases',          // admin only

            // Complaints
            'view-complaints',
            'manage-complaints',

            // Reports
            'view-reports',

            // Evidence
            'upload-evidence',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── Create roles and assign permissions ─────────────────────────

        // Admin — all permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($permissions);

        // Officer — all except delete-* and manage-officers
        $officerPermissions = [
            'view-criminals',
            'create-criminals',
            'edit-criminals',
            'view-cases',
            'create-cases',
            'edit-cases',
            'view-complaints',
            'manage-complaints',
            'view-reports',
            'upload-evidence',
        ];

        $officerRole = Role::firstOrCreate(['name' => 'officer']);
        $officerRole->syncPermissions($officerPermissions);
    }
}
