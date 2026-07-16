<?php

namespace App\Imports;

use App\Models\Project;
use App\Models\Repository;
use Maatwebsite\Excel\Concerns\ToModel;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class RepositoriesImport implements ToModel
{
    public function model(array $row)
    {

        $project = Project::select('id')->where('name', '=', $row[0])
            ->first();

        return new Repository([
            'project_id' => $project->id,
            'name' => $row[1],
            'url' => $row[2],
            'provider' => $row[3],
            'is_private' => $row[4],
        ]);
    }
}
