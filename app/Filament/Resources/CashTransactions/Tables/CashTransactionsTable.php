<?php

namespace App\Filament\Resources\CashTransactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CashTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')->label('Cabang')
                    ->searchable(),
                TextColumn::make('user.name')->label('Petugas')
                    ->searchable(),
                TextColumn::make('type')->label('Tipe')
                    ->badge(),
                TextColumn::make('reference_type')->label('Tipe Referensi')
                    ->searchable(),
                TextColumn::make('reference_id')->label('ID Referensi')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_method')->label('Metode Bayar')
                    ->badge(),
                TextColumn::make('amount')->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('transaction_date')->label('Tanggal Transaksi')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
