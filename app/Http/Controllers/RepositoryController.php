<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repositories = Repository::paginate();
        return response()->json([
            'data' => [
                "repositories" => $repositories
            ],
            "success" => true
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string',
            'url' => 'required|url',
            'provider' => 'nullable|string'
        ]);

        $repository = Repository::create($data);

        return response()->json([
            'success' => true,
            'data' => $repository
        ],);
    }

    /**
     * Display the specified resource.
     */
    public function show(Repository $repository)
    {
        $repository->load([
            "project" => function ($q) {
                $q->select("id", "name");
            }
        ]);

        return response()->json([
            'data' => [
                'repository' => $repository
            ],
            "success" => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Repository $repository)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'url' => 'required|url',
            'provider' => 'nullable|string'
        ]);

        $repository->update($data);

        return response()->json([
            'success' => true,
            'data' => [
                "repository" => $repository
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Repository $repository)
    {
        $repository->delete();

        return response()->json([
            'data' => [
                "repository" => $repository
            ],
            'message' => 'Repository deleted successfully',
            'success' => true,
        ]);
    }
}
