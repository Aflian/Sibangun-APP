<?php

namespace App\Filament\Resources\DeliveryOrderItems\Pages;

use App\Filament\Resources\DeliveryOrderItems\DeliveryOrderItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryOrderItems extends ListRecords
{
    protected static string $resource = DeliveryOrderItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
