<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Statuses;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            for ($i = 0; $i < 30; $i++) {

                $task = $user->tasks()->create([
                    'title' => fake()->realText(15),
                    'status' => Statuses::toArray()[array_rand(Statuses::toArray())],
                    'description' => fake()->realText(100),
                    'team_id' => $user->teams()->first()->id
                ]);

                for ($j = 0; $j < 2; $j++) {
                    $task->comments()->create([
                        'content' => fake()->realText(50),
                        'user_id' => $user->id,
                    ]);
                }
            }
        }
    }
}
