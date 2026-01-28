<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subtasks = Subtask::select('id', 'task_id', 'description', 'completed_at')->get();

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
            'sort_no' => 'nullable|integer',
        ]);

        $validatedData['completed_at'] = null;

        $subtask = Subtask::create($validatedData);



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
        $subtask = Subtask::findOrFail($id);

        $validatedData = $request->validate([
            'description' => 'nullable|string',
            'sort_no' => 'nullable|integer',
        ]);

        if (!$subtask->completed_at && $request->has('completed_at')) {
            $validatedData['completed_at'] = now();
        } else {
            $validatedData['completed_at'] = null;
        }

        $subtask->update($validatedData);

        if ($subtask->completed_at) {
            $subtask->completed_at = $subtask->completed_at->format('Y-m-d H:i:s');
        }

        return response()->json([
            'success' => true,
            'message' => 'Subtask updated successfully',
            'data' => [
                'subtask' => $subtask,
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subtask $subtask)
    {
        $subtask->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subtask deleted successfully',
        ]);
    }
}
