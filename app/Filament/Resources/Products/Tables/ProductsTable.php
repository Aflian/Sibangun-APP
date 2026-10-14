<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')->label('Kategori')
                    ->searchable(),
                TextColumn::make('unit.name')->label('Satuan')
                    ->searchable(),
                TextColumn::make('code')->label('Kode')
                    ->searchable(),
                TextColumn::make('barcode')->label('Barcode')
                    ->searchable(),
                TextColumn::make('name')->label('Nama')
                    ->searchable(),
                TextColumn::make('brand')->label('Merek')
                    ->searchable(),
                TextColumn::make('purchase_price')->label('Harga Beli')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('selling_price')->label('Harga Jual')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('minimum_stock')->label('Stok Minimum')
                    ->numeric()
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
                TextColumn::make('deleted_at')->label('Dihapus Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
