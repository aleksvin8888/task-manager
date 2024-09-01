<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->has(Team::factory()->count(1))
            ->create([
            'name' => 'Alex',
            'email' => 'alex@go.vn.ua',
            'email_verified_at' => now(),
            'password' => Hash::make('dracaris'),
        ]);

        Team::factory(5)
            ->has(User::factory()->count(3))
            ->create();

        $this->call([
           TaskSeeder::class
        ]);
    }
}
