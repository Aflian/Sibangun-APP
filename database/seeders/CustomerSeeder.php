<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'code' => 'CST-001',
                'name' => 'PT Jaya Konstruksi',
                'customer_type' => 'company',
                'phone' => '0761234567',
                'email' => 'finance@jayakonstruksi.com',
                'address' => 'Jl. Riau No. 45, Pekanbaru',
                'credit_limit' => 50000000,
                'status' => 'active',
            ],
            [
                'code' => 'CST-002',
                'name' => 'CV Mitra Bangun',
                'customer_type' => 'company',
                'phone' => '0761234568',
                'email' => 'cv.mitrabangun@gmail.com',
                'address' => 'Jl. Sudirman No. 89, Pekanbaru',
                'credit_limit' => 30000000,
                'status' => 'active',
            ],
            [
                'code' => 'CST-003',
                'name' => 'Budi Santoso',
                'customer_type' => 'individual',
                'phone' => '081234567890',
                'email' => null,
                'address' => 'Jl. Melati No. 12, Pekanbaru',
                'credit_limit' => 5000000,
                'status' => 'active',
            ],
            [
                'code' => 'CST-004',
                'name' => 'Toko Material Jaya',
                'customer_type' => 'company',
                'phone' => '0765234567',
                'email' => 'tokomaterialjaya@gmail.com',
                'address' => 'Jl. Pasar Duri No. 23, Duri',
                'credit_limit' => 20000000,
                'status' => 'active',
            ],
            [
                'code' => 'CST-005',
                'name' => 'Siti Aminah',
                'customer_type' => 'individual',
                'phone' => '082345678901',
                'email' => null,
                'address' => 'Jl. Durian No. 7, Duri',
                'credit_limit' => 3000000,
                'status' => 'active',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(['code' => $customer['code']], $customer);
        }
    }
}
