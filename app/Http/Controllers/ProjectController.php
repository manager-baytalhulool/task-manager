<?php

namespace App\Http\Controllers;

use App\Imports\ProjectsImport;
use App\Models\Project;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->for == "select") {
            $projects = Project::select("id", "name")
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json([
                'success' => true,
                'data' => [
                    'projects' => $projects,
                ]
            ]);
        }
        $projects = Project::select("id", "name", 'live_url', 'demo_url', 'is_live', "started_at")
            ->search($request->search)
            ->when($request->is_live !== null && $request->is_live !== '', function ($query) use ($request) {
                return $query->where('is_live', $request->is_live);
            })
            ->when($request->start_date, function ($query, $startDate) {
                return $query->where('started_at', '>=', $startDate);
            })
            ->when($request->end_date, function ($query, $endDate) {
                return $query->where('started_at', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->paginate();
        return response()->json([
            'success' => true,
            'data' => [
                'projects' => $projects,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            "started_at" => "required",
            "is_live" => "nullable|boolean",
            "live_url" => "nullable|url",
            "demo_url" => "nullable|url",
        ]);

        $project = Project::create($data);

        return response()->json([
            'success' => true,
            "message" => "Project created successfully.",
            'data' => ["project" => $project],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {

        $project->load(['tasks' => function ($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }, 'tasks.assignee', 'repositories']);
        return response()->json([
            'success' => true,
            'data' => [
                'project' => $project
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'live_url' => 'nullable|url',
            'demo_url' => 'nullable|url',
            'started_at' => 'required|date',
            'is_live' => 'boolean'
        ]);

        $project->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'project' => $project,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
            'project' => $project,
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv'
        ]);

        $file = $request->file('file');

        Excel::import(new ProjectsImport, $file);

        return response()->json([
            'success' => true,
            'message' => 'Projects imported successfully',

        ], 200);
    }
}
