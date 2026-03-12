<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Order matters — roles must exist before users are assigned roles.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,  // 1. Create roles & permissions first
            AdminSeeder::class,                 // 2. System admin user
            OfficerSeeder::class,               // 3. Officer accounts
            CriminalSeeder::class,              // 4. Criminal profiles
            CrimeRecordSeeder::class,           // 5. Crime cases linked to criminals & officers
        ]);
    }
}
