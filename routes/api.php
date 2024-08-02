<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

//Usuarios
use App\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:api')->get('/auth/me', [AuthController::class, 'me']);

// rutas de verificacion
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\PasswordResetController;

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/resetform/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTaskController;

/*
Route::middleware('auth:api')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    Route::post('/projects/{projectId}/add-users', [ProjectController::class, 'addUsers']);

    Route::get('/users', [ProjectController::class, 'fetchUsers']);
    Route::post('/projects/{projectId}/update-role', [ProjectController::class, 'updateUserRole']);
    Route::get('/projects/{projectId}/users', [ProjectController::class, 'fetchAddedUsers']);

    Route::get('/api/projects/{projectId}/users/{userId}/role', [ProjectController::class, 'getUserRole']);
    Route::put('/api/projects/{projectId}/users/{userId}/role', [ProjectController::class, 'updateUserRole']);

});
*/

Route::middleware('auth:api')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    Route::get('/users', [ProjectController::class, 'fetchUsers']);
    Route::post('/projects/{projectId}/add-users', [ProjectController::class, 'addUsers']);
    Route::get('/projects/{projectId}/users', [ProjectController::class, 'fetchAddedUsers']);
    Route::get('/projects/{projectId}/users/{userId}/role', [ProjectController::class, 'getUserRole']);
    Route::put('/projects/{projectId}/users/{userId}/role', [ProjectController::class, 'updateUserRole']);

    Route::get('/projects/{projectId}/tasks', [ProjectTaskController::class, 'index']);
    Route::post('/projects/{projectId}/tasks', [ProjectTaskController::class, 'store']);
    Route::get('/projects/{projectId}/tasks/{taskId}', [ProjectTaskController::class, 'show']);
    Route::put('/projects/{projectId}/tasks/{taskId}', [ProjectTaskController::class, 'update']);
    Route::delete('/projects/{projectId}/tasks/{taskId}', [ProjectTaskController::class, 'destroy']);
    Route::get('/projects/{projectId}/user-role/{userId}', [ProjectController::class, 'getUserRole']);

    Route::post('/projects/{projectId}/tasks/{taskId}/assign', [ProjectTaskController::class, 'assignUserToTask']);
    Route::get('/projects/{projectId}/tasks/{taskId}/users', [ProjectTaskController::class, 'getTaskUsers']);

    Route::get('/users/{userId}/assigned-tasks', [ProjectTaskController::class, 'getAssignedTasks']);

    Route::post('/tasks/{taskId}/submit', [ProjectTaskController::class, 'submitFile']);
    Route::get('/tasks/{taskId}/files', [ProjectTaskController::class, 'getTaskFiles']);
});

