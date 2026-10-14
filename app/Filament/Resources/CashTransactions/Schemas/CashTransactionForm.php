<?php

namespace App\Filament\Resources\CashTransactions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CashTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(null),
                Select::make('type')
                    ->options([
                        'income' => 'Income',
                        'expense' => 'Expense',
                        'sale' => 'Sale',
                        'purchase' => 'Purchase',
                        'receivable_payment' => 'Receivable payment',
                        'payable_payment' => 'Payable payment',
                        'transfer' => 'Transfer',
                        'adjustment' => 'Adjustment',
                    ])
                    ->required(),
                TextInput::make('reference_type')
                    ->default(null),
                TextInput::make('reference_id')
                    ->numeric()
                    ->default(null),
                Select::make('payment_method')
                    ->options(['cash' => 'Cash', 'transfer' => 'Transfer', 'qris' => 'Qris', 'debit' => 'Debit'])
                    ->default('cash')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                DateTimePicker::make('transaction_date')
                    ->required(),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
