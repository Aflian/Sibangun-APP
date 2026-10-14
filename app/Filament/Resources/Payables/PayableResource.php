<?php

namespace App\Filament\Resources\Payables;

use App\Filament\Resources\Payables\Pages\CreatePayable;
use App\Filament\Resources\Payables\Pages\EditPayable;
use App\Filament\Resources\Payables\Pages\ListPayables;
use App\Filament\Resources\Payables\Schemas\PayableForm;
use App\Filament\Resources\Payables\Tables\PayablesTable;
use App\Models\Payable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PayableResource extends Resource
{
    protected static ?string $model = Payable::class;

    protected static ?string $navigationLabel = 'Hutang Vendor';

    protected static ?string $pluralModelLabel = 'Hutang Vendor';

    protected static ?string $modelLabel = 'Hutang';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|BackedEnum|\UnitEnum|null $navigationGroup = 'Keuangan';

    public static function form(Schema $schema): Schema
    {
        return PayableForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayablesTable::configure($table);
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
            'index' => ListPayables::route('/'),
            'create' => CreatePayable::route('/create'),
            'edit' => EditPayable::route('/{record}/edit'),
        ];
    }
}
