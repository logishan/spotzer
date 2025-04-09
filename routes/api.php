<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\TaskGroup;
use App\Models\TaskType;
use App\Models\ComplexityLevel;
use App\Models\TaskTypeComplexity;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Get task types and complexity levels for a task group (public endpoint - no auth required)
Route::get('/task-groups/{taskGroup}/fields', [TaskController::class, 'getFormFields'])
    ->withoutMiddleware(['auth']);

// Get complexities for a specific task type (public endpoint - no auth required)
Route::get('/task-type-complexities', [TaskController::class, 'getTaskTypeComplexities'])
    ->withoutMiddleware(['auth']);

// Get complexity levels for a task group (public endpoint - no auth required)
Route::get('/task-complexity-levels', [TaskController::class, 'getTaskComplexityLevels'])
    ->withoutMiddleware(['auth']);

// Test endpoint
Route::get('/test-response', function() {
    return response()->json([
        'fields' => [
            [
                'name' => 'test_field',
                'type' => 'text',
                'required' => true,
                'label' => 'Test Field',
                'options' => []
            ]
        ],
        'taskTypeComplexities' => [
            'Test Type' => [
                [
                    'id' => 1,
                    'display_name' => 'Low (30 min)'
                ],
                [
                    'id' => 2,
                    'display_name' => 'Medium (60 min)'
                ]
            ]
        ],
        'success' => true
    ]);
});
