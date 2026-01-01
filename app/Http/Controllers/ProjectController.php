<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::select("id", "name", "started_at")
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
}
