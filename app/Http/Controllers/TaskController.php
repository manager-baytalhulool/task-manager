<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->for == "select") {
            $tasks = Task::select('id', 'description')
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json([
                'success' => true,
                'data' => [
                    'tasks' => $tasks
                ]
            ]);
        }
        $tasks = Task::select(
            'id',
            'description',
            'created_by',
            'assignee_id',
            'status',
            'started_at',
            'completed_at',
            'project_id',
            'created_at'
        )
            ->search($request->search)
            ->when(Auth::user()->role_id !== 1, function ($query) {
                return $query->where('assignee_id', Auth::id());
            })
            ->when($request->assignee_id, function ($query, $assigneeId) {
                return $query->where('assignee_id', $assigneeId);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->project_id, function ($query, $projectId) {
                return $query->where('project_id', $projectId);
            })
            ->with([
                'assignee' => function ($q) {
                    $q->select('id', 'name');
                },
                'createdBy' => function ($q) {
                    $q->select('id', 'name');
                },
                'project' => function ($q) {
                    $q->select('id', 'name');
                },
            ])
            ->orderBy('created_at', 'desc')
            ->paginate();
        // dd($tasks);
        return response()->json([
            'success' => true,
            'data' => [
                'tasks' => $tasks
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|max:1024',
            'assignee_id' => Auth::user()->role_id === 1 ? 'required|exists:users,id' : 'nullable',
            'project_id' => 'required|exists:projects,id',
        ]);
        if (Auth::user()->role_id !== 1) {
            $data['assignee_id'] = Auth::id();
        }
        // "status" => ["required", Rule::enum(TaskStatusEnum::class)]
        $data['created_by'] = Auth::id();
        $data['status'] = TaskStatusEnum::CREATED->value;
        $task = Task::create($data);

        $task->load([
            "assignee" => function ($q) {
                $q->select('id', 'name');
            },
            "project" => function ($q) {
                $q->select('id', 'name');
            }
        ]);

        return response()->json([
            'message'   => 'Task created successfully.',
            'data'      => ['task' => $task],
            'status'    => 'success'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load([
            "assignee" => function ($q) {
                $q->select('id', 'name');
            },
            "createdBy" => function ($q) {
                $q->select('id', 'name');
            },
            "subtasks" => function ($q) {
                $q->select('id', 'task_id', 'description', 'completed_at', 'sort_no');
            },
            "comments"
        ]);
        return response()->json([
            'data' => ['task' => $task],
            'success' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'description' => 'sometimes|required|max:1024',
            'assignee_id' => 'sometimes|required|exists:users,id',
            'status' => 'sometimes|required|string'
        ]);
        if ($task->status === TaskStatusEnum::COMPLETION_APPROVED->value) {
            return response()->json([
                'message' => 'Cannot change anything of a completed task. You can delete it if needed.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (isset($data['status'])) {
            $developerStatuses = [
                TaskStatusEnum::IN_PROGRESS->value,
                TaskStatusEnum::ON_HOLD->value,
                TaskStatusEnum::IN_REVIEW->value
            ];

            if (in_array($data['status'], $developerStatuses) && Auth::id() !== $task->assignee_id) {
                return response()->json([
                    'message' => 'Only the assignee can start, hold or submit this task for review.',
                ], Response::HTTP_FORBIDDEN);
            }
        }

        $task->update($data);

        $task->load([
            "assignee" => function ($q) {
                $q->select('id', 'name');
            },
            "project" => function ($q) {
                $q->select('id', 'name');
            }
        ]);

        return response()->json([
            'data' => ['task' => $task],
            'message' => 'Task updated successfully.',
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'data' => ['task' => $task],
            'message' => 'Task deleted successfully.',
            'success' => true
        ]);
    }
}
