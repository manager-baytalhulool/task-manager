<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            TaskTypeSeeder::class,
            UserSeeder::class, // This creates your specific admin/users
        ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
    }
}
