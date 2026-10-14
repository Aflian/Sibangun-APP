<?php

namespace App\Filament\Resources\DeliveryOrders\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeliveryOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->default(null),
                Select::make('stock_transfer_id')
                    ->relationship('stockTransfer', 'id')
                    ->default(null),
                TextInput::make('delivery_number')
                    ->required(),
                DatePicker::make('delivery_date')
                    ->required(),
                Textarea::make('destination_address')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('driver_name')
                    ->default(null),
                TextInput::make('vehicle_number')
                    ->default(null),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'prepared' => 'Prepared',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('draft')
                    ->required(),
                Textarea::make('note')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('created_by')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
