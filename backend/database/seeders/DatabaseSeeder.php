<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Tables;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@digiliza.com',
            'password' => bcrypt('admin_digiliza'),
            'type' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Evandro',
            'email' => 'evandro@teste.com',
            'password' => bcrypt('evandro_teste'),
            'type' => 'admin',
        ]);

        Tables::factory(15)->create();
        Reservation::factory(50)->create();
    }
}
