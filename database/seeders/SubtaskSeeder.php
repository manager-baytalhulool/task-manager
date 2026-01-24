<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SubtaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Saare tasks fetch karein
        $tasks = Task::all();

        if ($tasks->isEmpty()) {
            $this->command->info('Pehle TaskSeeder run karein kyunke koi task nahi mila.');
            return;
        }

        $subtaskTemplates = [
            "Requirement analysis",
            "Initial research",
            "Drafting the logic",
            "Writing the code",
            "Code review",
            "Fixing minor bugs",
            "Testing on local",
            "Documentation update",
            "Final verification",
            "Deployment check"
        ];

        foreach ($tasks as $task) {
            // Har task ke liye 5 se 10 ke beech random subtasks
            $numberOfSubtasks = rand(5, 10);

            for ($i = 1; $i <= $numberOfSubtasks; $i++) {
                // Randomly description pick karein ya template use karein
                $description = $subtaskTemplates[array_rand($subtaskTemplates)] . " ($i)";

                Subtask::create([
                    'task_id' => $task->id,
                    'description' => $description,
                    // Agar main task completed hai, to subtask ko bhi randomly complete dikha sakte hain
                    'completed_at' => (rand(0, 1) == 1) ? Carbon::now() : null,
                    'sort_no' => $i,
                ]);
            }
        }

        $this->command->info('Subtasks kamyabi se seed ho gaye hain!');
    }
}
