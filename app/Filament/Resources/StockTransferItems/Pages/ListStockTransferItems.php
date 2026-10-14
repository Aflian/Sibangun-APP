<?php

namespace App\Filament\Resources\StockTransferItems\Pages;

use App\Filament\Resources\StockTransferItems\StockTransferItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStockTransferItems extends ListRecords
{
    protected static string $resource = StockTransferItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
