<?php

namespace App\Filament\Resources\BranchStocks;

use App\Filament\Resources\BranchStocks\Pages\CreateBranchStock;
use App\Filament\Resources\BranchStocks\Pages\EditBranchStock;
use App\Filament\Resources\BranchStocks\Pages\ListBranchStocks;
use App\Filament\Resources\BranchStocks\Schemas\BranchStockForm;
use App\Filament\Resources\BranchStocks\Tables\BranchStocksTable;
use App\Models\BranchStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BranchStockResource extends Resource
{
    protected static ?string $model = BranchStock::class;

    protected static ?string $navigationLabel = 'Stok Cabang';

    protected static ?string $pluralModelLabel = 'Stok Cabang';

    protected static ?string $modelLabel = 'Stok Cabang';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|BackedEnum|\UnitEnum|null $navigationGroup = 'Inventori & Stok';

    public static function form(Schema $schema): Schema
    {
        return BranchStockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchStocksTable::configure($table);
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
            'index' => ListBranchStocks::route('/'),
            'create' => CreateBranchStock::route('/create'),
            'edit' => EditBranchStock::route('/{record}/edit'),
        ];
    }
}
