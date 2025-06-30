<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pengeluaran';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = $this->getExpenseData();

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengeluaran',
                    'data' => $data['values'],
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => '#f59e0b20',
                    'fill' => true,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getExpenseData(): array
    {
        $days = 7;
        $data = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');
            
            // Karena date_shopping adalah string, gunakan perbandingan string sederhana
            $total = DB::table('expenses')
                ->where('date_shopping', $date->format('Y-m-d'))
                ->sum('amount');
            
            $data[] = $total;
        }

        return [
            'labels' => $labels,
            'values' => $data,
        ];
    }
} 