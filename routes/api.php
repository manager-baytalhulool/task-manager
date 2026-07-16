<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth:sanctum')
        ->name('logout');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/me', [\App\Http\Controllers\UserController::class, 'show'])->defaults('id', 'me');
});



// Route::middleware('auth:sanctum')->group(function () {
//     // Route::get('/users/me', [UserController::class, 'show']);

//     Route::apiResource('users', UserController::class);

//     Route::post('/projects/import', [ProjectController::class, 'import']);
//     Route::apiResource('projects', ProjectController::class);

//     Route::post('/repostiories/import', [RepositoryController::class, 'import']);
//     Route::apiResource('repositories', RepositoryController::class);

//     Route::apiResource('task-types', TaskTypeController::class);

//     Route::apiResource('tasks', TaskController::class);

//     Route::apiResource('subtasks', SubTaskController::class);

//     Route::post('comments', [CommentController::class, 'store']);

//     Route::apiResource('roles', RoleController::class);
// });
