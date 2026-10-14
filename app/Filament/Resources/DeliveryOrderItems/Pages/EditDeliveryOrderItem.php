<?php

namespace App\Filament\Resources\DeliveryOrderItems\Pages;

use App\Filament\Resources\DeliveryOrderItems\DeliveryOrderItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDeliveryOrderItem extends EditRecord
{
    protected static string $resource = DeliveryOrderItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
