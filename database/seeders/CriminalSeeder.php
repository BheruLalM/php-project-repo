<?php

namespace Database\Seeders;

use App\Models\Criminal;
use Illuminate\Database\Seeder;

class CriminalSeeder extends Seeder
{
    public function run(): void
    {
        $criminals = [
            [
                'full_name'        => 'Rajesh Kumar Verma',
                'alias'            => 'Raju',
                'date_of_birth'    => '1985-04-12',
                'physical_markers' => 'Scar on left cheek, tattooed snake on right arm, 5\'9" tall, medium build',
                'nationality'      => 'Indian',
                'address'          => '14, Railway Colony, Bhopal, MP 462001',
                'status'           => 'wanted',
                'photo_path'       => null,
            ],
            [
                'full_name'        => 'Mohammed Aslam Sheikh',
                'alias'            => 'The Ghost',
                'date_of_birth'    => '1979-11-30',
                'physical_markers' => 'Bald, 6\'1", heavy build, burn scar on right hand',
                'nationality'      => 'Indian',
                'address'          => '78, Shivaji Nagar, Mumbai, MH 400015',
                'status'           => 'arrested',
                'photo_path'       => null,
            ],
            [
                'full_name'        => 'Priya Suresh Nair',
                'alias'            => 'P.S.',
                'date_of_birth'    => '1992-07-18',
                'physical_markers' => '5\'4", slim, small mole below left eye, speaks multiple languages',
                'nationality'      => 'Indian',
                'address'          => '32, MG Road, Kochi, KL 682035',
                'status'           => 'released',
                'photo_path'       => null,
            ],
            [
                'full_name'        => 'Sukhdev Singh Gill',
                'alias'            => 'Lucky',
                'date_of_birth'    => '1975-02-05',
                'physical_markers' => '6\'0", stocky, beard, missing tip of left index finger, old bullet scar on left thigh',
                'nationality'      => 'Indian',
                'address'          => '56, Sector 12, Chandigarh, PB 160012',
                'status'           => 'wanted',
                'photo_path'       => null,
            ],
            [
                'full_name'        => 'Kavitha Ramachandran',
                'alias'            => null,
                'date_of_birth'    => '1988-09-22',
                'physical_markers' => '5\'6", medium build, wears glasses, distinctive red birthmark on neck',
                'nationality'      => 'Indian',
                'address'          => '11, Anna Nagar, Chennai, TN 600040',
                'status'           => 'arrested',
                'photo_path'       => null,
            ],
        ];

        foreach ($criminals as $data) {
            Criminal::firstOrCreate(['full_name' => $data['full_name']], $data);
        }
    }
}
