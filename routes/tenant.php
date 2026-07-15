<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

// Route::middleware([
//     'web',
//     InitializeTenancyByDomain::class,
//     PreventAccessFromCentralDomains::class,
// ])->group(function () {
//     Route::get('/', function () {
//         return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
//     });
// });

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Tenant Authentication Routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
        Route::post('logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])
            ->middleware('auth:sanctum')
            ->name('logout');
    });

    // Tenant Protected API Resources
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [\App\Http\Controllers\UserController::class, 'show'])->defaults('id', 'me');
        Route::apiResource('users', \App\Http\Controllers\UserController::class);
        Route::post('/projects/import', [\App\Http\Controllers\ProjectController::class, 'import']);
        Route::apiResource('projects', \App\Http\Controllers\ProjectController::class);
        Route::post('/repositories/import', [\App\Http\Controllers\RepositoryController::class, 'import']);
        Route::apiResource('repositories', \App\Http\Controllers\RepositoryController::class);
        Route::apiResource('task-types', \App\Http\Controllers\TaskTypeController::class);
        Route::apiResource('tasks', \App\Http\Controllers\TaskController::class);
        Route::apiResource('subtasks', \App\Http\Controllers\SubTaskController::class);
        Route::post('comments', [\App\Http\Controllers\CommentController::class, 'store']);
        Route::apiResource('roles', \App\Http\Controllers\RoleController::class);
    });
});
