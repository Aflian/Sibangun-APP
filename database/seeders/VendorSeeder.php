<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            [
                'code' => 'VND-001',
                'name' => 'PT Semen Indonesia',
                'contact_person' => 'Andi Wijaya',
                'phone' => '0215551234',
                'email' => 'sales@semengresik.com',
                'address' => 'Gedung Semen Indonesia, Jakarta',
                'status' => 'active',
            ],
            [
                'code' => 'VND-002',
                'name' => 'UD Besi Kuat',
                'contact_person' => 'Herman Gunawan',
                'phone' => '0761555234',
                'email' => 'udbesi@gmail.com',
                'address' => 'Jl. Industri No. 56, Pekanbaru',
                'status' => 'active',
            ],
            [
                'code' => 'VND-003',
                'name' => 'PT Dulux Indonesia',
                'contact_person' => 'Sarah Lestari',
                'phone' => '0215552345',
                'email' => 'b2b@dulux.co.id',
                'address' => 'Kawasan Industri MM2100, Bekasi',
                'status' => 'active',
            ],
            [
                'code' => 'VND-004',
                'name' => 'Supplier Pasir Sungai',
                'contact_person' => 'Pak Yanto',
                'phone' => '081298765432',
                'email' => null,
                'address' => 'Sungai Kampar, Duri',
                'status' => 'active',
            ],
            [
                'code' => 'VND-005',
                'name' => 'PT Rucika',
                'contact_person' => 'Dedi Kurniawan',
                'phone' => '0215553456',
                'email' => 'sales.riau@rucika.co.id',
                'address' => 'Jl. Gatot Subroto, Jakarta',
                'status' => 'active',
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::firstOrCreate(['code' => $vendor['code']], $vendor);
        }
    }
}
