<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
     protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 12;


    protected function getStats(): array
    {
           //Total Pemasukan Bulan Ini
           $incomeThisMonth = Transaction::whereHas('category', function ($query) {
                $query->where('type', 'income');
            })
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

            // Total Pengeluaran Bulan ini
            $expenseThisMonth = Transaction::whereHas('category', function($query){
                $query->where('type', 'expense');
            })
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

            // Saldo saat ini (Total semua pemasukan - Total semua pengeluaran)
            $totalIncome = Transaction::whereHas('category', function($query){
                $query->where("type", "income");
            })->sum('amount');

            $totalExpense = Transaction::whereHas('category', function($query) {
                $query->where("type", "expense");
            })->sum('amount');

            $currentBalance = $totalIncome - $totalExpense;

        return [
            Stat::make("Saldo saat ini", "Rp ". number_format($currentBalance, 0, ',', '.'))
            ->description("Total saldo keseluruhan")
            ->descriptionIcon("heroicon-m-banknotes")
            ->color($currentBalance >= 0 ? 'success' : 'danger')
            ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make("Pemasukan Bulan ini", "Rp ". number_format($incomeThisMonth, 0, ',', '.'))
            ->description(now()->format("F Y"))
            ->descriptionIcon("heroicon-m-arrow-trending-up")
            ->color('success')
            ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Pengeluaran Bulan Ini', 'Rp ' . number_format($expenseThisMonth, 0, ',', '.'))
            ->description(now()->format('F Y'))
            ->descriptionIcon('heroicon-m-arrow-trending-down')
            ->color('danger')
            ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
        ];
    }
}
