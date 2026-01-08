<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Transaction;
use Filament\Tables\Columns\TextColumn;

class LatestTransactions extends TableWidget
{
     protected static ?int $sort = 3;

     protected static ?string $heading = "Transaksi Terakhir";

    protected int | string | array $columnSpan = 12;

    public function table(Table $table): Table
    {
        return $table
            ->query( Transaction::query()->latest('transaction_date')->limit(10))
            ->columns([
                TextColumn::make('transaction_date')
                ->label('Tanggal')
                ->date('d M Y')
                ->sortable(),

                TextColumn::make('category.name')
                ->label('Kategori')
                ->badge()
                ->color(fn (Transaction $record ): string => 
                    $record->category->type === 'income' ? 'success' : 'danger'
                 ),

                TextColumn::make('amount')
                ->label('Jumlah')
                ->money("IDR")
                ->sortable(),

                TextColumn::make("description")
                ->label('Deskripsi')
                ->limit(40)
                ->searchable()
            ])  
            ->defaultSort('transaction_date', 'desc')
            ->filters([
                
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
