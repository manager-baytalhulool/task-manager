<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Repository;
use Illuminate\Database\Seeder;

class RepositorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sab projects ko fetch karte hain
        $projects = Project::all();

        if ($projects->isEmpty()) {
            $this->command->info('Pehle ProjectSeeder run karein kyunke koi project nahi mila.');
            return;
        }

        foreach ($projects as $project) {
            // Har project ke liye 1 ya 2 repositories bana dete hain
            Repository::create([
                'project_id' => $project->id,
                'name' => $project->name . ' - Main Repo',
                'url' => 'https://gitlab.com/baytalhulool/' . str($project->name)->slug(),
                'provider' => 'gitlab',
            ]);

            // Agar project e-commerce hai to ek frontend repo bhi add kar dete hain
            if ($project->name == 'E-Commerce Platform') {
                Repository::create([
                    'project_id' => $project->id,
                    'name' => 'E-Commerce Frontend',
                    'url' => 'https://github.com/baytalhulool/shop-frontend',
                    'provider' => 'github',
                ]);
            }
        }
    }
}
