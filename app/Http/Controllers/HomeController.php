<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class HomeController extends Controller
{
    use AuthorizesAdmin;

    public function index()
    {
        // Listado general de usuarios para la vista de administración
        $usuarios = User::all();
        return view('home', compact('usuarios'));
    }

    public function storeUser(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,becario',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'Usuario creado correctamente.');
    }

    public function deleteUser($id)
    {
        $this->authorizeAdmin();

        if ($id == 1) {
            return back()->with('error', 'No puedes eliminar al administrador principal.');
        }

        if ($id == Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        User::findOrFail($id)->delete();
        return back()->with('success', 'Usuario eliminado correctamente.');
    }

    public function toggleAdmin($id)
    {
        $this->authorizeAdmin();

        if ($id == 1) {
            return back()->with('error', 'No puedes cambiar el rol del administrador principal.');
        }

        if ($id == Auth::id()) {
            return back()->with('error', 'No puedes cambiar tu propio rol.');
        }

        $user = User::findOrFail($id);
        $user->role = ($user->role === 'admin') ? 'becario' : 'admin';
        $user->save();

        return back()->with('success', 'Permisos actualizados con éxito.');
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,becario',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        return DB::transaction(function () use ($request, $id) {
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($user->save()) {
                return back()->with('success', 'Usuario actualizado correctamente.');
            }

            return back()->with('error', 'No se pudo guardar el usuario.');
        });
    }
}