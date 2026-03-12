<?php

namespace Database\Seeders;

use App\Models\CrimeRecord;
use App\Models\Criminal;
use App\Models\User;
use Illuminate\Database\Seeder;

class CrimeRecordSeeder extends Seeder
{
    public function run(): void
    {
        // Retrieve seeded data
        $criminals = Criminal::all()->keyBy('full_name');
        $officers  = User::officers()->get();

        $officerA = $officers->firstWhere('badge_number', 'B-1001'); // Inspector Rajan
        $officerB = $officers->firstWhere('badge_number', 'B-1002'); // SI Meera

        $records = [
            [
                'case_number'         => 'CRMS-2024-00001',
                'crime_type'          => 'Theft',
                'description'         => 'Suspect broke into a jewellery shop on MG Road and stole gold ornaments worth ₹12 lakhs.',
                'location'            => 'MG Road, Bhopal, MP',
                'date_of_occurrence'  => '2024-01-15',
                'status'              => 'closed',
                'assigned_officer_id' => $officerA?->id,
                'criminal_id'         => $criminals['Rajesh Kumar Verma']?->id,
                'archived_at'         => null,
            ],
            [
                'case_number'         => 'CRMS-2024-00002',
                'crime_type'          => 'Assault',
                'description'         => 'Violent assault reported near railway station. Victim hospitalised with serious injuries.',
                'location'            => 'Central Railway Station, Mumbai, MH',
                'date_of_occurrence'  => '2024-04-22',
                'status'              => 'under_investigation',
                'assigned_officer_id' => $officerB?->id,
                'criminal_id'         => $criminals['Mohammed Aslam Sheikh']?->id,
                'archived_at'         => null,
            ],
            [
                'case_number'         => 'CRMS-2024-00003',
                'crime_type'          => 'Fraud',
                'description'         => 'Online banking fraud targeting senior citizens. Estimated loss ₹35 lakhs across 20+ victims.',
                'location'            => 'Kochi, Kerala',
                'date_of_occurrence'  => '2024-06-10',
                'status'              => 'under_investigation',
                'assigned_officer_id' => $officerA?->id,
                'criminal_id'         => $criminals['Priya Suresh Nair']?->id,
                'archived_at'         => null,
            ],
            [
                'case_number'         => 'CRMS-2024-00004',
                'crime_type'          => 'Drug Trafficking',
                'description'         => 'Large-scale narcotics consignment intercepted at state border. 40 kg contraband seized.',
                'location'            => 'Punjab Border Checkpoint, Chandigarh',
                'date_of_occurrence'  => '2024-09-05',
                'status'              => 'open',
                'assigned_officer_id' => $officerB?->id,
                'criminal_id'         => $criminals['Sukhdev Singh Gill']?->id,
                'archived_at'         => null,
            ],
            [
                'case_number'         => 'CRMS-2025-00005',
                'crime_type'          => 'Cybercrime',
                'description'         => 'Suspect allegedly hacked into government payroll system and redirected funds to off-shore accounts.',
                'location'            => 'Chennai, Tamil Nadu',
                'date_of_occurrence'  => '2025-02-14',
                'status'              => 'open',
                'assigned_officer_id' => $officerA?->id,
                'criminal_id'         => $criminals['Kavitha Ramachandran']?->id,
                'archived_at'         => null,
            ],
        ];

        foreach ($records as $data) {
            CrimeRecord::firstOrCreate(['case_number' => $data['case_number']], $data);
        }
    }
}
