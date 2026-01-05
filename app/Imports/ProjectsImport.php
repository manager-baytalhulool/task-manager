<?php

namespace App\Imports;


use App\Models\Project;
use Maatwebsite\Excel\Concerns\ToModel;

class ProjectsImport implements ToModel
{
    public function model(array $row)
    {
        return new Project([
            'name' => $row[0],
            'live_url' => $row[1],
            'demo_url' => $row[2],
            'started_at' => $row[3],
            'is_live' => $row[4],
        ]);
    }
}
