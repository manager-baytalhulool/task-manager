<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'E-Commerce Platform',
                'live_url' => 'https://example-shop.com',
                'demo_url' => 'https://demo.example-shop.com',
                'started_at' => '2024-01-01',
                'is_live' => true,
            ],
            [
                'name' => 'Task Manager API',
                'live_url' => null,
                'demo_url' => 'https://staging.task-api.com',
                'started_at' => '2024-05-15',
                'is_live' => false,
            ],
            [
                'name' => 'Portfolio Website',
                'live_url' => 'https://my-portfolio.com',
                'demo_url' => null,
                'started_at' => '2023-12-20',
                'is_live' => true,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
