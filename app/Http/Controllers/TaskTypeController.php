<?php

namespace App\Http\Controllers;

use App\Models\TaskType;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     if ($request->for == "select") {
    //         $taskTypes = TaskType::select("id", "name")->get();
    //         return response()->json([
    //             'success' => true,
    //             'data' => [
    //                 'task_types' => $taskTypes
    //             ]
    //         ]);
    //     }
    // }

    public function index(Request $request)
    {
        if ($request->for == "select") {
            $taskTypes = TaskType::select("id", "name")->get();
            return response()->json([
                'success' => true,
                'data' => [
                    'task_types' => $taskTypes
                ]
            ]);
        }

        $taskTypes = TaskType::select("id", "name")
            ->search($request->search)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return response()->json([
            'success' => true,
            'data' => [
                'task_types' => $taskTypes
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
        ]);

        $taskType = TaskType::create($data);

        return response()->json([
            'message'   => 'Task type created successfully.',
            'data'      => ["task_type" => $taskType],
            'status'    => 'success'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskType $taskType)
    {
        return response()->json([
            "data" => ['task_type' => $taskType],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskType $taskType)
    {
        $data = $request->validate([
            'name' => 'required|string',
        ]);

        $taskType->update($data);

        return response()->json([
            "data" => ['task_type' => $taskType],
            'message' => 'Task Type updated successfully.',
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskType $taskType)
    {
        $taskType->delete();

        return response()->json([
            "data" => ['task_type' => $taskType],
            'message' => 'Task Type deleted successfully.',
            'success' => true
        ]);
    }
}
