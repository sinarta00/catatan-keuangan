<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

// input
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

// tabel
use Filament\Tables\Columns\TextColumn;

// filter
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Tag;

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $modelLabel = 'Kategori';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
         return $schema
        ->components([
            TextInput::make('name')->label('Nama Kategori')->required()->maxLength(255),
            Select::make('type')->label('Tipe')->options([
                'income' => 'Pemasukan',
                'expense' => 'Pengeluaran'
            ])->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
       return $table->columns([
        TextColumn::make('name')->label('Nama')->searchable(),
        TextColumn::make('type')
        ->label('Tipe')
        ->colors([
            'success' => 'income',
            'danger' => 'expense',
        ])
        ->formatStateUsing(fn ($state) => $state === 'income'
            ? 'Pemasukan'
            : 'Pengeluaran'
        ),
        TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i')->sortable(),
        ])->filters([
            SelectFilter::make('type')
            ->label("Tipe")
            ->options([
                'income' => 'Pemasukan',
                'expense' => 'Pengeluaran'
            ])
        ])->recordActions([
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
