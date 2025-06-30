<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseStats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Karena date_shopping adalah string, gunakan perbandingan string sederhana
        $todayExpense = DB::table('expenses')
            ->where('date_shopping', $today->format('Y-m-d'))
            ->sum('amount');
            
        $thisMonthExpense = DB::table('expenses')
            ->where('date_shopping', '>=', $thisMonth->format('Y-m-d'))
            ->sum('amount');
            
        $lastMonthExpense = DB::table('expenses')
            ->where('date_shopping', '>=', $lastMonth->format('Y-m-d'))
            ->where('date_shopping', '<', $thisMonth->format('Y-m-d'))
            ->sum('amount');

        $totalExpenses = Expense::count();

        return [
            Stat::make('Pengeluaran Hari Ini', 'Rp' . number_format($todayExpense))
                ->description('Total pengeluaran hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($todayExpense > 0 ? 'success' : 'gray'),

            Stat::make('Pengeluaran Bulan Ini', 'Rp' . number_format($thisMonthExpense))
                ->description('Total pengeluaran bulan ini')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('Total Transaksi', $totalExpenses)
                ->description('Jumlah transaksi keseluruhan')
                ->descriptionIcon('heroicon-m-receipt-refund')
                ->color('info'),
        ];
    }
} 