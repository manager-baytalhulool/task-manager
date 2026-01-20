<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use App\Enums\TaskStatusEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $users = User::all();

        if ($projects->isEmpty() || $users->isEmpty()) {
            $this->command->info('Pehle Projects aur Users seed karein!');
            return;
        }

        $taskDescriptions = [
            "Fix login page CSS issues",
            "Implement JWT authentication",
            "Setup AWS S3 bucket for file uploads",
            "Create database schema for inventory",
            "Optimize API response time",
            "Write unit tests for User Controller",
            "Integrate Stripe payment gateway",
            "Design landing page mockup",
            "Fix bug in shopping cart total",
            "Add multi-language support",
            "Refactor repository pattern",
            "Setup CI/CD pipeline",
            "Create user profile edit feature",
            "Export reports to Excel/PDF",
            "Implement real-time notifications with WebSockets",
            "Audit security vulnerabilities",
            "Update documentation for API",
            "Improve mobile responsiveness",
            "Add social media login (Google/Github)",
            "Setup Redis caching",
            "Fix email template formatting",
            "Implement soft deletes for all models",
            "Create admin dashboard widgets",
            "Optimize database queries for search",
            "Setup backup cron jobs"
        ];

        foreach ($taskDescriptions as $index => $desc) {
            $project = $projects->random();
            $creator = $users->random();
            $assignee = $users->random();

            // Random status select karne ke liye (Agar Enum use kar rahe hain)
            // Agar Enum nahi chal raha toh direct string bhi de sakte hain
            $statuses = ['created', 'in_progress', 'completed', 'on_hold'];
            $randomStatus = $statuses[array_rand($statuses)];

            Task::create([
                'description' => $desc,
                'created_by' => $creator->id,
                'assignee_id' => $assignee->id,
                'project_id' => $project->id,
                'status' => $randomStatus, // Yahan TaskStatusEnum::CREATED->value bhi use kar sakte hain
                'is_paid' => rand(0, 1),
                'started_at' => Carbon::now()->subDays(rand(1, 10)),
                'completed_at' => ($randomStatus == 'completed') ? Carbon::now() : null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $this->command->info('25 Tasks kamyabi se seed ho gaye hain!');
    }
}
