<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subtasks = SubTask::select('id', 'task_id', 'description', 'completed_at')->get();

        return response()->json([
            'success' => true,
            'message' => 'Subtasks retrieved successfully',
            'data' => [
                'subtasks' => $subtasks,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'description' => 'nullable|string',
            'completed_at' => 'nullable|date',
        ]);

        $subtask = SubTask::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Subtask created successfully',
            'data' => [
                'subtask' => $subtask,
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subtask = SubTask::findOrFail($id);
        if (!$subtask->completed_at) {
            $validatedData = $request->validate([
                'task_id' => 'required|exists:tasks,id',
                'description' => 'nullable|string',
                'completed_at' => 'nullable|date',
            ]);

            $subtask->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Subtask updated successfully',
                'data' => [
                    'subtask' => $subtask,
                ],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Completed subtask cannot be updated',
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubTask $subtask)
    {
        $subtask->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subtask deleted successfully',
        ]);
    }
}
