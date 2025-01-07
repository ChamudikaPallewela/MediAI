<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\DB;
use App\Models\Drugs;
use App\Models\MedicineUsage;

class MedicineUsageChart extends Chart
{
    public function __construct()
    {
        parent::__construct();

        // Fetch medicine usage data
        $medicineUsageData = MedicineUsage::select(
            DB::raw('drug_id'),
            DB::raw('DATE_FORMAT(usage_date, "%M") as month'),
            DB::raw('COUNT(*) as total_usage')
        )
        ->groupBy('month', 'drug_id')
        ->orderBy('month')
        ->get();

        // Prepare data for chart
        $usageByMonth = [];
        foreach ($medicineUsageData as $usage) {
            $usageByMonth[$usage->month][$usage->drug_id] = $usage->total_usage;
        }

        // Get distinct months
        $months = array_keys($usageByMonth);

        // Prepare datasets
        $datasets = [];
        $drugs = Drugs::all()->keyBy('id');

        foreach ($drugs as $drug) {
            $data = [];
            foreach ($months as $month) {
                $data[] = $usageByMonth[$month][$drug->id] ?? 0;
            }
            $datasets[] = [
                'label' => $drug->name,
                'data' => $data,
                'backgroundColor' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
            ];
        }

        // Build the chart
        $this->labels($months);
        foreach ($datasets as $dataset) {
            $this->dataset($dataset['label'], 'bar', $dataset['data'])
                 ->backgroundColor($dataset['backgroundColor']);
        }

        $this->options([
            'responsive' => true,
            'legend' => ['display' => true],
            'scales' => [
                'xAxes' => [['stacked' => true]],
                'yAxes' => [['stacked' => true]],
            ],
        ]);
    }
}
