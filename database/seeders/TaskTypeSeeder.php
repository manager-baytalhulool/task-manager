<?php

namespace Database\Seeders;

use App\Models\TaskType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taskTypes = ["front-end", "back-end"];
        foreach ($taskTypes as $taskType) {
            TaskType::create([
                'name' => $taskType
            ]);
        }
    }
}
