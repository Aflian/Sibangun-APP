<?php

namespace App\Filament\Resources\DeliveryOrderItems;

use App\Filament\Resources\DeliveryOrderItems\Pages\CreateDeliveryOrderItem;
use App\Filament\Resources\DeliveryOrderItems\Pages\EditDeliveryOrderItem;
use App\Filament\Resources\DeliveryOrderItems\Pages\ListDeliveryOrderItems;
use App\Filament\Resources\DeliveryOrderItems\Schemas\DeliveryOrderItemForm;
use App\Filament\Resources\DeliveryOrderItems\Tables\DeliveryOrderItemsTable;
use App\Models\DeliveryOrderItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeliveryOrderItemResource extends Resource
{
    protected static ?string $model = DeliveryOrderItem::class;

    protected static ?string $navigationLabel = 'Detail Surat Jalan';

    protected static ?string $pluralModelLabel = 'Detail Surat Jalan';

    protected static ?string $modelLabel = 'Detail Surat Jalan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|BackedEnum|\UnitEnum|null $navigationGroup = 'Pengiriman';

    public static function form(Schema $schema): Schema
    {
        return DeliveryOrderItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeliveryOrderItemsTable::configure($table);
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
            'index' => ListDeliveryOrderItems::route('/'),
            'create' => CreateDeliveryOrderItem::route('/create'),
            'edit' => EditDeliveryOrderItem::route('/{record}/edit'),
        ];
    }
}
