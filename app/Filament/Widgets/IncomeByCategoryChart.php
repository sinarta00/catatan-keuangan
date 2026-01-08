<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class IncomeByCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Pemasukan per Kategori';

    protected static ?int $sort = 4;

    public ?string $filter = 'month';
    
    protected int | string | array $columnSpan = 6;

    protected ?string $maxHeight = '400px';

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
                     ->where('categories.type', 'income')
                     ->selectRaw('categories.name, SUM(transactions.amount) as total')
                     ->groupBy('categories.name');
        

        if ($this->filter == 'month'){
            $query->whereMonth('transactions.transaction_date', now()->month)
                  ->whereYear('transactions.transaction_date', now()->year);
        } elseif($this->filter == 'year'){
            $query->whereYear('transactions.transaction_date', now()->year);
        }

        $data = $query->get();

        $labels = $data->pluck('name')->toArray();
        $amounts = $data->pluck('total')->toArray();

        $colors = [
            'rgba(34, 197, 94, 0.8)',   // green
            'rgba(16, 185, 129, 0.8)',  // emerald
            'rgba(20, 184, 166, 0.8)',  // teal
            'rgba(6, 182, 212, 0.8)',   // cyan
            'rgba(14, 165, 233, 0.8)',  // sky
            'rgba(59, 130, 246, 0.8)',  // blue
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $amounts,
                    'backgroundColor' => array_slice($colors, 0, count($labels)),
                    'borderColor' => '#fffffff',
                    'borderWidth' => 2,
                ]
                ],
            'labels' => $labels
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getFilters(): ?array {
        return [
            'month' => 'Bulan ini',
            'year' => 'Tahun ini',
            'all' => 'Semua waktu'
        ];
    }
}
