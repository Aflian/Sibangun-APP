<?php

namespace App\Filament\Resources\Purchases\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PurchasesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')->label('Cabang')
                    ->searchable(),
                TextColumn::make('vendor.name')->label('Vendor')
                    ->searchable(),
                TextColumn::make('user.name')->label('Petugas')
                    ->searchable(),
                TextColumn::make('purchase_number')->label('Nomor Pembelian')
                    ->searchable(),
                TextColumn::make('purchase_date')->label('Tanggal Pembelian')
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
                TextColumn::make('payment_method')->label('Metode Bayar')
                    ->badge(),
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
