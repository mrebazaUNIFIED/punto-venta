<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            EmpresaSeeder::class,
            DemoSeeder::class,
        ]);

        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin POS',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole('admin');
    }
}
