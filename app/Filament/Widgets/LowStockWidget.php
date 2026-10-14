<?php

namespace App\Filament\Widgets;

use App\Models\BranchStock;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseTableWidget;

class LowStockWidget extends BaseTableWidget
{
    protected static ?string $heading = 'Stok Menipis';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                BranchStock::query()
                    ->with(['product', 'branch'])
                    ->whereColumn('quantity', '<=', 'products.minimum_stock')
                    ->join('products', 'products.id', '=', 'branch_stocks.product_id')
                    ->select('branch_stocks.*')
            )
            ->columns([
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->sortable(),
                TextColumn::make('branch.name')
                    ->label('Cabang')
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Stok')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('danger')
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 0, ',', '.')),
                TextColumn::make('product.minimum_stock')
                    ->label('Minimum')
                    ->numeric()
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 0, ',', '.')),
            ])
            ->defaultSort('quantity', 'asc')
            ->filters([
                SelectFilter::make('branch_id')
                    ->label('Cabang')
                    ->relationship('branch', 'name'),
            ])
            ->paginated([5, 10, 25, 50])
            ->defaultPaginationPageOption(10);
    }
}
