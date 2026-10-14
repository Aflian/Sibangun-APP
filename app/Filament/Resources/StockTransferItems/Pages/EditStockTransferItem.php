<?php

namespace App\Filament\Resources\StockTransferItems\Pages;

use App\Filament\Resources\StockTransferItems\StockTransferItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStockTransferItem extends EditRecord
{
    protected static string $resource = StockTransferItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
