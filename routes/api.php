<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas API para tu aplicación. Estas
| rutas se cargan mediante el RouteServiceProvider y todas se asignan al
| grupo de middleware "api". ¡Haz algo grandioso!
|
*/

// Controladores de autenticación
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\PasswordResetController;

// Rutas de autenticación
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:api')->get('/auth/me', [AuthController::class, 'me']);

// Rutas de verificación de email y restablecimiento de contraseña
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/resetform/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

// Controladores de proyectos y tareas
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\UserController;

// Rutas protegidas por autenticación
Route::middleware('auth:api')->group(function () {
    // Rutas de proyectos
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

    // Rutas de usuarios
    Route::get('/users', [UserController::class, 'fetchUsers']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/users/{id}/role', [UserController::class, 'getRole']);
    Route::put('/users/{id}/role', [UserController::class, 'updateRole']);

    // Rutas relacionadas con proyectos y roles de usuarios
    Route::post('/projects/{projectId}/add-users', [ProjectController::class, 'addUsers']);
    Route::get('/projects/{projectId}/users', [ProjectController::class, 'fetchAddedUsers']);
    Route::get('/projects/{projectId}/users/{userId}/role', [ProjectController::class, 'getUserRole']);
    Route::put('/projects/{projectId}/users/{userId}/role', [ProjectController::class, 'updateUserRole']);
    Route::get('/projects/{projectId}/user-role/{userId}', [ProjectController::class, 'getUserRole']);

    // Rutas de tareas de proyectos
    Route::get('/projects/{projectId}/tasks', [ProjectTaskController::class, 'index']);
    Route::post('/projects/{projectId}/tasks', [ProjectTaskController::class, 'store']);
    Route::get('/projects/{projectId}/tasks/{taskId}', [ProjectTaskController::class, 'show']);
    Route::put('/projects/{projectId}/tasks/{taskId}', [ProjectTaskController::class, 'update']);
    Route::delete('/projects/{projectId}/tasks/{taskId}', [ProjectTaskController::class, 'destroy']);

    // Rutas para asignación de tareas y gestión de usuarios en tareas
    Route::post('/projects/{projectId}/tasks/{taskId}/assign', [ProjectTaskController::class, 'assignUserToTask']);
    Route::get('/projects/{projectId}/tasks/{taskId}/users', [ProjectTaskController::class, 'getTaskUsers']);
    Route::get('/users/{userId}/assigned-tasks', [ProjectTaskController::class, 'getAssignedTasks']);

    // Rutas para archivos de tareas
    Route::post('/tasks/{taskId}/submit', [ProjectTaskController::class, 'submitFile']);
    Route::get('/tasks/{taskId}/files', [ProjectTaskController::class, 'getTaskFiles']);
});
