<?php

namespace App\Filament\Resources\StockMovements\Tables;

use App\Models\Purchase;
use App\Models\Sale;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')->label('Cabang')
                    ->searchable(),
                TextColumn::make('product.name')->label('Produk')
                    ->searchable(),
                TextColumn::make('user.name')->label('Petugas')
                    ->searchable(),
                TextColumn::make('reference_type')
                    ->label('Referensi')
                    ->badge()
                    ->colors([
                        'primary' => fn (string $state): bool => str_contains($state, 'Sale'),
                        'warning' => fn (string $state): bool => str_contains($state, 'Purchase'),
                        'info' => fn (string $state): bool => str_contains($state, 'Transfer'),
                        'success' => fn (string $state): bool => str_contains($state, 'Adjustment'),
                        'gray' => fn (string $state): bool => $state === null,
                    ])
                    ->formatStateUsing(fn (string $state): ?string => match ($state) {
                        Sale::class => 'Penjualan',
                        Purchase::class => 'Pembelian',
                        StockTransfer::class => 'Transfer Stok',
                        StockMovement::class => 'Penyesuaian',
                        default => null,
                    }),
                TextColumn::make('type')->label('Tipe')
                    ->badge(),
                TextColumn::make('quantity')->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock_before')->label('Stok Sebelum')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock_after')->label('Stok Sesudah')
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
