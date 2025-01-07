<?php

namespace App\Http\Controllers;

use App\Models\MedicineUsage;
use Illuminate\Support\Facades\DB;

class MedicineUsageController extends Controller
{
    public function showMedicineUsageChart()
    {
        $medicineUsages = DB::table('medicine_usages')
            ->join('drugs', 'medicine_usages.drug_id', '=', 'drugs.id')
            ->selectRaw('month, COUNT(*) as count, drugs.drug_name')
            ->groupBy('month', 'drugs.drug_name')
            ->orderByRaw("CASE
                WHEN month = 'January' THEN 1
                WHEN month = 'February' THEN 2
                WHEN month = 'March' THEN 3
                WHEN month = 'April' THEN 4
                WHEN month = 'May' THEN 5
                WHEN month = 'June' THEN 6
                WHEN month = 'July' THEN 7
                WHEN month = 'August' THEN 8
                WHEN month = 'September' THEN 9
                WHEN month = 'October' THEN 10
                WHEN month = 'November' THEN 11
                WHEN month = 'December' THEN 12
            END")
            ->get();

        $chartData = [];

        foreach ($medicineUsages as $usage) {
            $chartData[$usage->drug_name][$usage->month] = $usage->count;
        }

        return view('medicine_usage_chart', compact('chartData'));
    }
}
