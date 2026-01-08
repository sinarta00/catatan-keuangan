<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Transaction;

class MonthlyChart extends ChartWidget
{
    protected ?string $heading = 'Ringkasan Bulanan';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 12;

    protected function getExtraAttributes(): array
    {
        return [
            'style' => '--widget-content-height: 1000px;',
        ];
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'responsive' => true,
            'animation' => false,
        ];
    }

    protected  ?string $pollingInterval = null;

    protected function getData(): array
    {   
        // Data 12 bulan terakhir
        $months = [];
        $incomeData = [];
        $expenseData = [];


        for ($i = 11; $i >= 0; $i--){
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            // Pemasukan
            $income = Transaction::whereHas('category', function($query) {
                $query->where('type', 'income');
            })
            ->whereMonth('transaction_date', $date->month)
            ->whereYear('transaction_date', $date->year)
            ->sum('amount');

            $incomeData[] = $income;

            // Pengeluaran
            $expense = Transaction::whereHas('category', function($query) {
                $query->where('type', 'expense');
            })
            ->whereMonth('transaction_date', $date->month)
            ->whereYear('transaction_date', $date->year)
            ->sum('amount');

            $expenseData[] = $expense;
        }



        return [
            //
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $incomeData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.2)',
                    'borderColor' => 'rgb(34, 197, 94)',    
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $expenseData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.2)',
                    'borderColor' => 'rgb(239, 68, 68)',    
                ],
            ],
            'labels' => $months
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
