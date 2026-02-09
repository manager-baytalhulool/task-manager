<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['task_id' => 'required|exists:tasks,id', 'body' => 'required', 'parent_id' => 'nullable|exists:comments,id']);
        $task = Task::findOrFail($request->task_id);
        $user = Auth::user();

        if (!$request->parent_id) {
            if ($user->id !== $task->assignee_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only assignee can post the first comment.'
                ], 403);
            }
        } else {
            $canReply = ($user->id == $task->assignee_id || $user->id == $task->created_by || $user->role_id == 1);
            if (!$canReply) {
                return response()->json(['message' => 'You can not reply.'], 403);
            }
        }

        $comment = Comment::create(['task_id' => $request->task_id, 'user_id' => $user->id, 'parent_id' => $request->parent_id, 'body' => $request->body]);
        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully',
            'comment' => $comment->load('user')
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
