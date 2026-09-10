<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers; // Asegúrate de tener esta línea
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // IMPORTANTE: Debes usar el trait para que el login funcione
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // Esta función intercepta el login exitoso
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('dashboard');
    }
    // En tu controlador de autenticación (ej: Auth\LoginController.php)
public function logout(Request $request)
{
    Auth::guard()->logout();
    $request->session()->invalidate();
    return redirect('/login'); // Aquí fuerzas a que vuelva al login
}
}