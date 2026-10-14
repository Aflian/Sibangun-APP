<?php

namespace App\Filament\Resources\SaleItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SaleItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sale.id')->label('ID Penjualan')
                    ->searchable(),
                TextColumn::make('product.name')->label('Produk')
                    ->searchable(),
                TextColumn::make('quantity')->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')->label('Harga Satuan')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('discount')->label('Diskon')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('subtotal')->label('Subtotal')
                    ->money('IDR')
                    ->sortable(),
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
