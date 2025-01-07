<?php

// In your DashboardController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Drugs;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\MedicineUsage;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch and format the chart data
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

        // Initialize chart data
        $chartData = [];

        // Populate chart data
        foreach ($medicineUsages as $usage) {
            if (!isset($chartData[$usage->month])) {
                $chartData[$usage->month] = [];
            }
            $chartData[$usage->month][$usage->drug_name] = $usage->count;
        }

        // Fetch other necessary data
        $drugCount = Drugs::count();
        $patientCount = Patient::count();
        $userCount = User::where('user_type', 'Admin')->count();

        // Pass data to the view
        return view('index', compact('drugCount', 'patientCount', 'userCount', 'chartData'));
    }
}