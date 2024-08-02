<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    // to-do paginate a los usuarios en fetchusers

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
     * get user.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * get user role.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRole($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['role' => $user->role]);
    }

    /**
     * get user role.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string'
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();

        return response()->json(['message' => 'Rol actualizado con éxito']);
    }
}
