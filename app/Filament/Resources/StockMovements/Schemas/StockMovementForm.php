<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(null),
                TextInput::make('reference_type')
                    ->default(null),
                TextInput::make('reference_id')
                    ->numeric()
                    ->default(null),
                Select::make('type')
                    ->options([
                        'in' => 'In',
                        'out' => 'Out',
                        'sale' => 'Sale',
                        'purchase' => 'Purchase',
                        'transfer_in' => 'Transfer in',
                        'transfer_out' => 'Transfer out',
                        'adjustment' => 'Adjustment',
                        'return' => 'Return',
                    ])
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('stock_before')
                    ->numeric()
                    ->default(null),
                TextInput::make('stock_after')
                    ->numeric()
                    ->default(null),
                Textarea::make('note')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
