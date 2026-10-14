<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use Filament\Resources\Pages\Page;

class FinancialReport extends Page
{
    protected static string $resource = SaleResource::class;

    protected string $view = 'filament.resources.sales.pages.financial-report';
}
