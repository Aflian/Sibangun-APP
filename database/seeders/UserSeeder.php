<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user (no branch)
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@sibangun.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Get branches
        $branches = Branch::all();

        if ($branches->isNotEmpty()) {
            // Create staff users for each branch
            foreach ($branches as $branch) {
                User::create([
                    'name' => 'Staff '.$branch->name,
                    'email' => 'staff-'.$branch->code.'@sibangun.com',
                    'password' => Hash::make('password'),
                    'role' => 'staff',
                    'branch_id' => $branch->id,
                    'status' => 'active',
                ]);
            }
        }
    }
}
