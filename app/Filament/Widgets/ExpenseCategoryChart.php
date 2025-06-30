<?php

namespace App\Filament\Widgets;

use App\Models\ExpenseItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ExpenseCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Pengeluaran Berdasarkan Kategori';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = $this->getCategoryData();

        return [
            'datasets' => [
                [
                    'label' => 'Total Pengeluaran',
                    'data' => $data['values'],
                    'backgroundColor' => [
                        '#f59e0b',
                        '#ef4444',
                        '#10b981',
                        '#3b82f6',
                        '#8b5cf6',
                        '#ec4899',
                        '#06b6d4',
                        '#84cc16',
                    ],
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function getCategoryData(): array
    {
        // Mengelompokkan berdasarkan nama item (sebagai proxy untuk kategori)
        $categoryData = ExpenseItem::select('name', DB::raw('SUM(subtotal) as total'))
            ->groupBy('name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $labels = [];
        $values = [];

        foreach ($categoryData as $item) {
            $labels[] = $item->name;
            $values[] = $item->total;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
} 