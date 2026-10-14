<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample branches
        Branch::create([
            'code' => 'PKB',
            'name' => 'Pekanbaru',
            'address' => 'Jl. Ahmad Yani No. 123, Pekanbaru',
            'phone' => '0761123456',
            'status' => 'active',
        ]);

        Branch::create([
            'code' => 'DRU',
            'name' => 'Duri',
            'address' => 'Jl. Sudirman No. 456, Duri',
            'phone' => '0765123456',
            'status' => 'active',
        ]);
    }
}
