<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crms.gov'],
            [
                'name'           => 'System Admin',
                'password'       => Hash::make('Admin@123'),
                'role'           => 'admin',
                'is_active'      => true,
                'badge_number'   => null,
                'rank'           => null,
                'station_branch' => null,
            ]
        );

        // Assign Spatie 'admin' role
        $admin->assignRole('admin');
    }
}
