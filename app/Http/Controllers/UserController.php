<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function store(Request $request)
    {
        session()->flash('form_type', 'create');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ], [
            'email.unique' => 'Este correo ya está registrado. Intenta con otro.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('dashboard')->with('success', 'Usuario creado exitosamente.')->with('form_type', 'create');
        
    }

    public function update(Request $request, User $user)
    {
        $request->merge(['id' => $user->id]);
        session()->flash('form_type', 'edit');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6|confirmed';
        }

        $validated = $request->validate($rules, [
            'email.unique' => 'Este correo ya está registrado. Intenta con otro.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($validated['password']),
            ]);
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('dashboard')->with('success', 'Usuario actualizado correctamente.')->with('form_type', 'edit');

    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('dashboard')->with('success', 'Usuario eliminado correctamente.');
    }

}

