<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskFile;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectTaskController extends Controller
{
    /**
     * Get all tasks for a project.
     *
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('viewTasks', $project);

        $tasks = $project->tasks;
        return response()->json($tasks);
    }

    /**
     * Display task to edit.
     *
     * @param  int  $project_id
     * @param  int  $task_id
     * @return \Illuminate\Http\Response
     */
    public function show($project_id, $task_id)
    {
        try {
            $project = Project::findOrFail($project_id);
            $this->authorize('editTasks', $project);
            $task = Task::findOrFail($task_id);

            return response()->json($task);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $projectId = $request->input('project_id');
        $project = Project::findOrFail($projectId);
        $this->authorize('editTasks', $project);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'is_completed' => 'boolean',
        ]);

        $task = Task::create($validated);

        // TODO - enviar email basico a Admin y a usuarios con la tarea
        $emails = ['danireiros@gmail.com'];
        $content = "Nueva tarea " . $request->input('title');
        $subject = "Nueva tarea " . $request->input('title');
        $mailController = new SendMailController();
        $mailController->sendEmails($emails, $content, $subject);

        return response()->json($task, 201);
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $project_id
     * @param  int  $task_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $project_id, $task_id)
    {
        $task = Task::findOrFail($task_id);
        $project = Project::findOrFail($project_id);
        $this->authorize('editTasks', $project);

        $validated = $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'is_completed' => 'boolean',
        ]);

        $task->update($validated);
        return response()->json($task);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  int  $project_id
     * @param  int  $task_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($project_id, $task_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('editTasks', $project);

        $task = Task::findOrFail($task_id);

        $task->delete();
        return response()->json(['message' => 'Tarea eliminada con éxito.']);
    }

    /**
     * Assign a user to a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @param  int  $taskId
     * @return \Illuminate\Http\Response
     */
    public function assignUserToTask(Request $request, $projectId, $taskId)
    {
        $task = Task::where('project_id', $projectId)->findOrFail($taskId);
        $user = User::findOrFail($request->input('user_id'));

        $task->users()->attach($user);

        // TODO - enviar email a Admin y a usuarios implicados
        $emails = [$user->email, 'danireiros@gmail.com'];
        $content = "Tarea ($task->title) asignada para " . $user->name;
        $subject = "Tarea ($task->title) asignada para " . $user->name;
        $mailController = new SendMailController();
        $mailController->sendEmails($emails, $content, $subject);

        return response()->json(['message' => 'Usuario asignado con éxito'], 200);
    }

    /**
     * Get all users assigned to a task.
     *
     * @param  int  $projectId
     * @param  int  $taskId
     * @return \Illuminate\Http\Response
     */
    public function getTaskUsers($projectId, $taskId)
    {
        $task = Task::where('project_id', $projectId)->findOrFail($taskId);
        $users = $task->users;
        return response()->json($users);
    }

    /**
     * Get all tasks assigned to a user.
     *
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function getAssignedTasks($userId)
    {
        $tasks = DB::table('tasks')
            ->join('task_user', 'tasks.id', '=', 'task_user.task_id')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('task_user.user_id', $userId)
            ->select('tasks.*', 'projects.name as project_name', 'task_user.completed as delivered')
            ->get()
            ->groupBy('project_name');

        return response()->json($tasks);
    }

    /**
     * Submit a file for a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $taskId
     * @return \Illuminate\Http\Response
     */
    public function submitFile(Request $request, $taskId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $task = Task::findOrFail($taskId);
        $user = $request->user();

        if (!$task->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'No tienes permiso para subir archivos para esta tarea.'], 403);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('task_files', 'public');

            $taskFile = new TaskFile();
            $taskFile->task_id = $taskId;
            $taskFile->url = $filePath;
            $taskFile->user_id = auth()->user()->id;
            $taskFile->save();

            $affectedRows = DB::table('task_user')
                          ->where('task_id', $taskId)
                          ->where('user_id', $user->id)
                          ->update(['completed' => true]);

            if ($affectedRows) {

                // TODO - enviar email a Admin y a usuarios implicados
                $emails = [$user->email, 'danireiros@gmail.com'];
                $content = "Tarea ($task->title) entregada por " . $user->name;
                $subject = "Tarea ($task->title) entregada por " . $user->name;
                $mailController = new SendMailController();
                $mailController->sendEmails($emails, $content, $subject);

                return response()->json(['message' => 'Tarea marcada como entregada con éxito'], 200);
            } else {
                return response()->json(['message' => 'Tarea actualizada y marcada como entregada'], 200);
            }
        }

        return response()->json(['message' => 'No se ha seleccionado ningún archivo.'], 400);
    }

    /**
     * Get all files for a task.
     *
     * @param  int  $taskId
     * @return \Illuminate\Http\Response
     */
    public function getTaskFiles($taskId)
    {
        $files = TaskFile::where('task_id', $taskId)
            ->with('user')
            ->get()
            ->map(function ($file) {
                return [
                    'id' => $file->id,
                    'file_name' => basename($file->file_path),
                    'url' => $file->url,
                    'user_name' => $file->user ? $file->user->name : 'Desconocido',
                    'uploaded_at' => $file->created_at->format('d-m-Y H:i:s'),
                ];
            });

        return response()->json($files);
    }
}
