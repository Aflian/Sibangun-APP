<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sale.id')->label('ID Penjualan')
                    ->searchable(),
                TextColumn::make('branch.name')->label('Cabang')
                    ->searchable(),
                TextColumn::make('payment_method')->label('Metode Bayar')
                    ->badge(),
                TextColumn::make('amount')->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('payment_date')->label('Tanggal Bayar')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('reference_number')->label('Nomor Referensi')
                    ->searchable(),
                TextColumn::make('created_by')->label('Dibuat Oleh')
                    ->numeric()
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
