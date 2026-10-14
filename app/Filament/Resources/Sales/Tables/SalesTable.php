<?php

namespace App\Filament\Resources\Sales\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')->label('Cabang')
                    ->searchable(),
                TextColumn::make('customer.name')->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('user.name')->label('Petugas')
                    ->searchable(),
                TextColumn::make('invoice_number')->label('Nomor Invoice')
                    ->searchable(),
                TextColumn::make('transaction_date')->label('Tanggal Transaksi')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('subtotal')->label('Subtotal')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('discount')->label('Diskon')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('tax')->label('Pajak')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('total')->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('paid_amount')->label('Sudah Dibayar')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status')->label('Status')
                    ->badge(),
                TextColumn::make('created_at')->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->label('Diperbarui Pada')
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
