<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\Pages\CreateTransaction;
use App\Filament\Resources\Transactions\Pages\EditTransaction;
use App\Filament\Resources\Transactions\Pages\ListTransactions;
use App\Filament\Resources\Transactions\Schemas\TransactionForm;
use App\Filament\Resources\Transactions\Tables\TransactionsTable;
use App\Models\Transaction;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = "Transaksi";
    
    protected static ?string $modelLabel = "Transaksi";

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('category_id')
            ->label('Kategori')
            ->relationship(name: 'category', titleAttribute: 'name')
            ->required()
            ->searchable()
            ->preload(),
            
            TextInput::make('amount')
            ->label("Jumlah")
            ->required()
            ->numeric()
            ->prefix('Rp')
            ->minValue(0),

            DatePicker::make('transaction_date')
            ->label('Tanggal Transaksi')
            ->required()
            ->default(now()),

            Textarea::make('description')
            ->label('Deskripsi')
            ->rows(3)
            ->columnSpanFull()

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('transaction_date')
            ->label('Tanggal Transaksi')
            ->date('d M Y')
            ->sortable(),

            TextColumn::make('category.name')
            ->label('Kategori')
            ->badge()
            ->color(fn (Transaction $record): string => 
                $record->category->type === 'income' ? 'success' : 'danger'
            )
            ->searchable(),

            TextColumn::make('amount')
            ->label('Jumlah')
            ->money('IDR')
            ->sortable(),

            TextColumn::make('description')
            ->label("Deskripsi")
            ->limit(30)
            ->searchable(),

            TextColumn::make('created_at')
            ->label("Dibuat")
            ->dateTime("d M Y H:i")
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true)

        ])->filters([
            SelectFilter::make('category_id')
            ->label('Kategori')
            ->relationship('category', 'name')
            ->searchable()
            ->preload()
        ])->recordActions([
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make()
        ])->toolbarActions([
            DeleteBulkAction::make()
        ]);
        
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransactions::route('/'),
            'create' => CreateTransaction::route('/create'),
            'edit' => EditTransaction::route('/{record}/edit'),
        ];
    }
}
