<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Sak', 'symbol' => 'Sak'],
            ['name' => 'Batang', 'symbol' => 'Btg'],
            ['name' => 'Rit', 'symbol' => 'Rit'],
            ['name' => 'Pail', 'symbol' => 'Pail'],
            ['name' => 'Pieces', 'symbol' => 'Pcs'],
            ['name' => 'Meter', 'symbol' => 'M'],
            ['name' => 'Kilogram', 'symbol' => 'Kg'],
            ['name' => 'Zak', 'symbol' => 'Zak'],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(['symbol' => $unit['symbol']], $unit);
        }
    }
}
