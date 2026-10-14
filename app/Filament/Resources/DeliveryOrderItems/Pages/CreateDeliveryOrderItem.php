<?php

namespace App\Filament\Resources\DeliveryOrderItems\Pages;

use App\Filament\Resources\DeliveryOrderItems\DeliveryOrderItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDeliveryOrderItem extends CreateRecord
{
    protected static string $resource = DeliveryOrderItemResource::class;
}
