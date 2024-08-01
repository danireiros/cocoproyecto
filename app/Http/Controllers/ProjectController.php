<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();
        return response()->json($projects);
    }

    /**
     * Display project.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $project = Project::with('creator')->findOrFail($id);
            return response()->json($project);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Proyecto no encontrado'], 404);
        }
    }

    /**
     * Store a newly created project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $project = Project::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'created_by' => auth()->user()->id,
        ]);

        return response()->json(['status' => 'Proyecto creado con éxito.', 'project' => $project], 201);
    }

    /**
     * Update a newly created project in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $project = Project::with('creator')->findOrFail($id);
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        return response()->json(['status' => 'Proyecto actualizado con éxito.', 'project' => $project]);
    }

    /**
     * Delete project in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $this->authorize('update', $project);
        $project->delete();

        return response()->json(['status' => 'Proyecto eliminado con éxito.']);
    }

    /**
     * Fetch a list of users.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchUsers()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'No tienes permisos para acceder a esta información.'], 403);
        }

        $users = User::all();
        return response()->json($users);
    }

    /**
     * Add users to project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function addUsers(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('update', $project);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        if ($project->users()->where('user_id', $validated['user_id'])->exists()) {
            return response()->json(['error' => 'El usuario ya está en el proyecto.'], 400);
        }

        $project->users()->attach($validated['user_id']);

        return response()->json(['status' => 'Usuario añadido con éxito.']);
    }

    /**
     * Fetch project users.
     *
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function fetchAddedUsers($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('viewUsers', $project);

        $members = $project->users;
        return response()->json($members);
    }

    /**
     * Get project user roles.
     *
     * @param  int  $projectId
     * @return \Illuminate\Http\Response
     */
    public function getUserRoles($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('viewUsers', $project);

        $userRoles = $project->users()->withPivot('role')->get()->map(function ($user) {
            return [
                'user_id' => $user->id,
                'role' => $user->pivot->role_id,
            ];
        });

        return response()->json($userRoles);
    }

    /**
     * Get project user role.
     *
     * @param  int  $projectId
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function getUserRole($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('viewUsers', $project);

        $user = $project->users()->where('user_id', $userId)->firstOrFail();
        return response()->json(['role' => $user->pivot->role]);
    }

    /**
     * Update project user role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $projectId
     * @param  int  $userId
     * @return \Illuminate\Http\Response
     */
    public function updateUserRole(Request $request, $projectId, $userId)
    {
        $request->validate([
            'role' => 'required|string|in:member,project_manager'
        ]);

        $project = Project::findOrFail($projectId);
        $this->authorize('viewUsers', $project);

        $project->users()->updateExistingPivot($userId, ['role' => $request->role]);

        return response()->json(['status' => 'Rol actualizado con éxito']);
    }
}
