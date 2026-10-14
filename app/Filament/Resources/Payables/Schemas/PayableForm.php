<?php

namespace App\Filament\Resources\Payables\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PayableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                Select::make('vendor_id')
                    ->relationship('vendor', 'name')
                    ->required(),
                Select::make('purchase_id')
                    ->relationship('purchase', 'id')
                    ->required(),
                DatePicker::make('due_date')
                    ->required(),
                TextInput::make('original_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('paid_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('remaining_amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'partial' => 'Partial',
                        'due_today' => 'Due today',
                        'overdue' => 'Overdue',
                        'paid' => 'Paid',
                    ])
                    ->default('unpaid')
                    ->required(),
            ]);
    }
}
