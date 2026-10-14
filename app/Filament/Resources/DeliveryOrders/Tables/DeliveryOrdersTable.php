<?php

namespace App\Filament\Resources\DeliveryOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeliveryOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')->label('Cabang')
                    ->searchable(),
                TextColumn::make('customer.name')->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('stockTransfer.id')->label('ID Item Transfer Stok')
                    ->searchable(),
                TextColumn::make('delivery_number')->label('Nomor Surat Jalan')
                    ->searchable(),
                TextColumn::make('delivery_date')->label('Tanggal Surat Jalan')
                    ->date()
                    ->sortable(),
                TextColumn::make('driver_name')->label('Nama Supir')
                    ->searchable(),
                TextColumn::make('vehicle_number')->label('Nomor Kendaraan')
                    ->searchable(),
                TextColumn::make('status')->label('Status')
                    ->badge(),
                TextColumn::make('created_by')->label('Dibuat Oleh')
                    ->numeric()
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
