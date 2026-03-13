<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo electronico es obligatorio.',
            'email.email' => 'Ingrese un correo electronico valido.',
            'password.required' => 'La contrasena es obligatoria.',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Verificar si el usuario esta activo
            if (!Auth::user()->active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Su cuenta ha sido desactivada. Contacte al administrador.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Redirigir segun rol
            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('biblioteca.index'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('biblioteca.index');
    }

    /**
     * Mostrar formulario de recuperacion de contrasena
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Enviar enlace de recuperacion
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'El correo electronico es obligatorio.',
            'email.email' => 'Ingrese un correo electronico valido.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => 'Se envio un enlace de recuperacion a su correo electronico.'])
            : back()->withErrors(['email' => 'No encontramos un usuario con ese correo electronico.']);
    }

    /**
     * Mostrar formulario de reset de contrasena
     */
    public function showResetPassword(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Procesar reset de contrasena
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'email.required' => 'El correo electronico es obligatorio.',
            'email.email' => 'Ingrese un correo electronico valido.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contrasenas no coinciden.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Su contrasena fue restablecida correctamente.')
            : back()->withErrors(['email' => 'El token de recuperacion es invalido o ha expirado.']);
    }
}
