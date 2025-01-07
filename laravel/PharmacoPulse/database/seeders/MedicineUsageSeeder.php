<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicineUsageSeeder extends Seeder
{
    public function run()
    {
        DB::table('medicine_usage')->insert([
            [
                'patient_id' => 1,
                'drug_id' => 1,
                'usage_date' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'patient_id' => 2,
                'drug_id' => 1,
                'usage_date' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Add more records as needed
        ]);
    }
}
