<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class ExpenseByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Pengeluaran Per Kategori';

    protected static ?int $sort = 5;

    // Filter bulan ini atau semua waktu
    public ?string $filter = 'month';

    protected int | string | array $columnSpan = 6;

    protected  ?string $maxHeight = '400px';

 

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'responsive' => true,
            'animation' => false,
        ];
    }


    protected function getData(): array
    {
        $query = Transaction::query()
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->where('categories.type', 'expense')
                ->selectRaw('categories.name, SUM(transactions.amount) as total')
                ->groupBy('categories.name');

        // Filter berdasarkan pilihan
        if ($this->filter === 'month'){
            $query->whereMonth('transactions.transaction_date', now()->month)
                  ->whereYear('transactions.transaction_date', now()->year);
        } elseif($this->filter === 'year') {
            $query->whereYear('transactions.transaction_date', now()->year);
        }


        $data = $query->get();

        $labels = $data->pluck('name')->toArray();
        $amounts = $data->pluck('total')->toArray();

        // Warna untuk pie chart
        $colors = [
            'rgba(239, 68, 68, 0.8)',   // red
            'rgba(249, 115, 22, 0.8)',  // orange
            'rgba(234, 179, 8, 0.8)',   // yellow
            'rgba(34, 197, 94, 0.8)',   // green
            'rgba(59, 130, 246, 0.8)',  // blue
            'rgba(147, 51, 234, 0.8)',  // purple
            'rgba(236, 72, 153, 0.8)',  // pink
            'rgba(168, 85, 247, 0.8)',  // violet
        ];

        return [
             'datasets' => [
                [
                    'label' => 'Pengeluaran',
                    'data' => $amounts,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
                
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

     protected function getFilters(): ?array
    {
        return [
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
            'all' => 'Semua Waktu',
        ];
    }
}
