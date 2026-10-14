<?php

namespace App\Filament\Resources\StockTransferItems;

use App\Filament\Resources\StockTransferItems\Pages\CreateStockTransferItem;
use App\Filament\Resources\StockTransferItems\Pages\EditStockTransferItem;
use App\Filament\Resources\StockTransferItems\Pages\ListStockTransferItems;
use App\Filament\Resources\StockTransferItems\Schemas\StockTransferItemForm;
use App\Filament\Resources\StockTransferItems\Tables\StockTransferItemsTable;
use App\Models\StockTransferItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockTransferItemResource extends Resource
{
    protected static ?string $model = StockTransferItem::class;

    protected static ?string $navigationLabel = 'Detail Transfer Stok';

    protected static ?string $pluralModelLabel = 'Detail Transfer Stok';

    protected static ?string $modelLabel = 'Detail Transfer Stok';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|BackedEnum|\UnitEnum|null $navigationGroup = 'Inventori & Stok';

    public static function form(Schema $schema): Schema
    {
        return StockTransferItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockTransferItemsTable::configure($table);
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
            'index' => ListStockTransferItems::route('/'),
            'create' => CreateStockTransferItem::route('/create'),
            'edit' => EditStockTransferItem::route('/{record}/edit'),
        ];
    }
}
