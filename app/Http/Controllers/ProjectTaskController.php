<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

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
        return response()->json($task, 201);
    }

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

    public function destroy($project_id, $task_id)
    {
        $project = Project::findOrFail($project_id);
        $this->authorize('editTasks', $project);

        $task = Task::findOrFail($task_id);

        $task->delete();
        return response()->json(['message' => 'Tarea eliminada con éxito.']);
    }

    public function assignUserToTask(Request $request, $projectId, $taskId)
    {
        $task = Task::where('project_id', $projectId)->findOrFail($taskId);
        $user = User::findOrFail($request->input('user_id'));

        $task->users()->attach($user);
        return response()->json(['message' => 'Usuario asignado con éxito'], 200);
    }

    public function getTaskUsers($projectId, $taskId)
    {
        $task = Task::where('project_id', $projectId)->findOrFail($taskId);
        $users = $task->users;
        return response()->json($users);
    }
}
