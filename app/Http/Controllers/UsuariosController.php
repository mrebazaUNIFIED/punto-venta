<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

class UsuariosController extends BaseController
{
    public function __construct()
    {
        // Middleware para proteger las rutas
        $this->middleware('auth');
    }

    /**
     * Muestra la lista de usuarios con sus roles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $usuarios = User::with('roles')->get();
        $roles = Role::all();
        return view('pages.panel.usuarios', compact('usuarios', 'roles'));
    }

    /**
     * Muestra la vista de roles (si es necesaria).
     *
     * @return \Illuminate\View\View
     */
    public function roles()
    {
        return view('pages.panel.roles');
    }

    /**
     * Crea un nuevo usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Asignar el rol
        $user->assignRole($validated['role']);

        // Mensaje de éxito
        return redirect()->route('panel.usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Actualiza un usuario existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Validar los datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|exists:roles,name',
        ]);

        // Actualizar el usuario
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Sincronizar roles
        $user->syncRoles([$validated['role']]);

        // Mensaje de éxito
        return redirect()->route('panel.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Actualiza la contraseña de un usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(Request $request, User $user)
    {
        // Validar los datos
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Actualizar la contraseña
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Mensaje de éxito
        return redirect()->route('panel.usuarios.index')->with('success', 'Contraseña actualizada correctamente.');
    }

    /**
     * Elimina un usuario.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Evitar que un usuario se elimine a sí mismo
        if (auth()->id() === $user->id) {
            return redirect()->route('panel.usuarios.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        // Eliminar el usuario
        $user->delete();

        // Mensaje de éxito
        return redirect()->route('panel.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}