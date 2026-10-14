<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Semen & Pasir', 'description' => 'Semen, pasir, kerikil, dan material cor-coran'],
            ['name' => 'Besi & Baja Ringan', 'description' => 'Besi beton, behel, baja ringan, dan rangka atap'],
            ['name' => 'Cat & Thinner', 'description' => 'Cat tembok, cat interior-eksterior, thinner, dan pelitur'],
            ['name' => 'Pipa PVC', 'description' => 'Pipa air, fitting, dan aksesoris saluran air'],
            ['name' => 'Bata Ringan', 'description' => 'Bata ringan/hebel dan batako'],
            ['name' => 'Alat & Paku', 'description' => 'Paku, perkakas, dan alat tukang'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description'], 'status' => 'active'],
            );
        }
    }
}
