<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;

class ProjectPolicy
{
    /**
     *
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        return $user->id === $project->created_by;
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        return $user->id === $project->created_by;
    }

    /**
     * add users to project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function addUsers(User $user, Project $project)
    {
        return $user->id === $project->created_by;
    }

    /**
     * view project users.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function viewUsers(User $user, Project $project)
    {
        return $user->id === $project->created_by;
    }

    /**
     * Verifica si el usuario puede ver las tareas del proyecto.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return bool
     */
    public function viewTasks(User $user, Project $project)
    {
        if ($user->id === $project->created_by) {
            return true;
        }

        $userInProject = $project->users()->wherePivot('user_id', $user->id)->exists();

        if ($userInProject) {
            $isProjectManager = $project->users()->wherePivot('user_id', $user->id)
                                                ->wherePivot('role', 'project_manager')
                                                ->exists();

            return $isProjectManager;
        }

        return false;
    }

    /**
     * Verifica si el usuario puede editar las tareas del proyecto.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return bool
     */
    public function editTasks(User $user, Project $project)
    {
        // Verificar si el usuario es el creador del proyecto
        if ($user->id === $project->created_by) {
            return true;
        }

        // Verificar si el usuario tiene el rol de project_manager en el proyecto
        return $project->users()->wherePivot('user_id', $user->id)
                                ->wherePivot('role', 'project_manager')
                                ->exists();
    }
}
