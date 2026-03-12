<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OfficerSeeder extends Seeder
{
    public function run(): void
    {
        $officers = [
            [
                'name'           => 'Inspector Rajan',
                'email'          => 'rajan@crms.gov',
                'password'       => Hash::make('Officer@123'),
                'badge_number'   => 'B-1001',
                'rank'           => 'Inspector',
                'station_branch' => 'Central Station',
                'role'           => 'officer',
                'is_active'      => true,
            ],
            [
                'name'           => 'SI Meera',
                'email'          => 'meera@crms.gov',
                'password'       => Hash::make('Officer@123'),
                'badge_number'   => 'B-1002',
                'rank'           => 'Sub Inspector',
                'station_branch' => 'East Station',
                'role'           => 'officer',
                'is_active'      => true,
            ],
        ];

        foreach ($officers as $data) {
            $officer = User::firstOrCreate(['email' => $data['email']], $data);
            $officer->assignRole('officer');
        }
    }
}
