<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MonthlyExpenseChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Pengeluaran Bulanan';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $data = $this->getMonthlyData();

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengeluaran Bulanan',
                    'data' => $data['values'],
                    'backgroundColor' => '#f59e0b',
                    'borderColor' => '#d97706',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function getMonthlyData(): array
    {
        $months = 6;
        $data = [];
        $labels = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            // Karena date_shopping adalah string, gunakan LIKE untuk filter bulan dan tahun
            $yearMonth = $date->format('Y-m');
            $total = DB::table('expenses')
                ->where('date_shopping', 'LIKE', $yearMonth . '%')
                ->sum('amount');
            
            $data[] = $total;
        }

        return [
            'labels' => $labels,
            'values' => $data,
        ];
    }
} 