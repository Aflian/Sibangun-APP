<?php

namespace App\Filament\Resources\Receivables\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReceivablesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')->label('Cabang')
                    ->searchable(),
                TextColumn::make('customer.name')->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('sale.id')->label('ID Penjualan')
                    ->searchable(),
                TextColumn::make('due_date')->label('Jatuh Tempo')
                    ->date()
                    ->sortable(),
                TextColumn::make('original_amount')->label('Nominal Awal')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('paid_amount')->label('Sudah Dibayar')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('remaining_amount')->label('Sisa Tagihan')
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
