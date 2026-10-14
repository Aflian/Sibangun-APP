<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\BranchStock;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure we have categories, units, branches
        $categories = Category::pluck('id', 'name')->toArray();
        $units = Unit::pluck('id', 'symbol')->toArray();
        $branches = Branch::pluck('id', 'code')->toArray();

        // Get IDs
        $semenCat = $categories['Semen & Pasir'] ?? 1;
        $besiCat = $categories['Besi & Baja Ringan'] ?? 2;
        $catCat = $categories['Cat & Thinner'] ?? 3;
        $pipaCat = $categories['Pipa PVC'] ?? 4;
        $bataCat = $categories['Bata Ringan'] ?? 5;
        $alatCat = $categories['Alat & Paku'] ?? 6;

        $unitSak = $units['Sak'] ?? 1;
        $unitBtg = $units['Batang'] ?? 2;
        $unitRit = $units['Rit'] ?? 3;
        $unitPail = $units['Pail'] ?? 4;
        $unitPcs = $units['Pieces'] ?? 5;

        $branchPkb = $branches['PKB'] ?? 1; // Pekanbaru
        $branchDrp = $branches['DRU'] ?? 2; // Duri

        // Define products
        $products = [
            [
                'code' => 'S-001',
                'barcode' => '8991234567890',
                'name' => 'Semen Tiga Roda 50kg',
                'brand' => 'Tiga Roda',
                'category_id' => $semenCat,
                'unit_id' => $unitSak,
                'purchase_price' => 58000,
                'selling_price' => 68500,
                'minimum_stock' => 10,
                'description' => 'Portland Composite Cement (PCC) untuk struktural dan non-struktural',
                'status' => 'active',
                'stock' => [
                    $branchPkb => 420,
                    $branchDrp => 380,
                ],
            ],
            [
                'code' => 'B-10U',
                'barcode' => '8991234567891',
                'name' => 'Besi Beton 10mm Ulir SNI',
                'brand' => 'Krakatau Steel',
                'category_id' => $besiCat,
                'unit_id' => $unitBtg,
                'purchase_price' => 72000,
                'selling_price' => 82000,
                'minimum_stock' => 5,
                'description' => 'Panjang standar 12 meter',
                'status' => 'active',
                'stock' => [
                    $branchPkb => 185,
                    $branchDrp => 160,
                ],
            ],
            [
                'code' => 'P-COR',
                'barcode' => '8991234567892',
                'name' => 'Pasir Hitam Cor (1 Rit)',
                'brand' => 'Lokal',
                'category_id' => $semenCat,
                'unit_id' => $unitRit,
                'purchase_price' => 650000,
                'selling_price' => 750000,
                'minimum_stock' => 2,
                'description' => 'Muatan dump truk Colt Diesel',
                'status' => 'active',
                'stock' => [
                    $branchPkb => 14,
                    $branchDrp => 12,
                ],
            ],
            [
                'code' => 'C-DLX',
                'barcode' => '8991234567893',
                'name' => 'Cat Dulux Catylac 25kg',
                'brand' => 'Dulux',
                'category_id' => $catCat,
                'unit_id' => $unitPail,
                'purchase_price' => 520000,
                'selling_price' => 635000,
                'minimum_stock' => 1,
                'description' => 'Super White #1501 Interior',
                'status' => 'active',
                'stock' => [
                    $branchPkb => 28,
                    $branchDrp => 25,
                ],
            ],
            [
                'code' => 'H-75',
                'barcode' => '8991234567894',
                'name' => 'Bata Ringan Hebel 7.5cm',
                'brand' => 'Hebel',
                'category_id' => $bataCat,
                'unit_id' => $unitPcs,
                'purchase_price' => 8500,
                'selling_price' => 9500,
                'minimum_stock' => 50,
                'description' => 'Ukuran presisi 60 x 20 x 7.5 cm',
                'status' => 'active',
                'stock' => [
                    $branchPkb => 850,
                    $branchDrp => 800,
                ],
            ],
            [
                'code' => 'PVC-3D',
                'barcode' => '8991234567895',
                'name' => 'Pipa PVC Rucika D 3"',
                'brand' => 'Rucika',
                'category_id' => $pipaCat,
                'unit_id' => $unitBtg,
                'purchase_price' => 88000,
                'selling_price' => 98000,
                'minimum_stock' => 10,
                'description' => 'Panjang 4m pipa saluran buang',
                'status' => 'active',
                'stock' => [
                    $branchPkb => 64,
                    $branchDrp => 60,
                ],
            ],
        ];

        foreach ($products as $productData) {
            $stockData = $productData['stock'];
            unset($productData['stock']);

            $product = Product::firstOrCreate(
                ['code' => $productData['code']],
                $productData
            );

            // Create branch stock for each branch
            foreach ($stockData as $branchId => $quantity) {
                BranchStock::updateOrCreate(
                    [
                        'branch_id' => $branchId,
                        'product_id' => $product->id,
                    ],
                    [
                        'quantity' => $quantity,
                        'reserved_quantity' => 0,
                    ]
                );
            }
        }
    }
}
