<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{
    /**
     * Dashboard del administrador
     */
    public function dashboard()
    {
        $cacheVersion = Cache::get('books:version', 1);
        [$stats, $recentBooks] = Cache::remember(
            "admin:dashboard:v{$cacheVersion}",
            now()->addMinutes(5),
            function () {
                $stats = [
                    'total_books' => Book::count(),
                    'total_libros' => Book::where('type', 'libro')->count(),
                    'total_audiolibros' => Book::where('type', 'audiolibro')->count(),
                    'total_users' => User::count(),
                    'total_docentes' => User::where('role', 'docente')->count(),
                ];

                $recentBooks = Book::with('uploader')->latest()->take(5)->get();

                return [$stats, $recentBooks];
            }
        );

        return view('admin.dashboard', compact('stats', 'recentBooks'));
    }

    /**
     * Listar usuarios
     */
    public function users(Request $request)
    {
        $query = User::withCount('books');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    /**
     * Formulario para crear un docente
     */
    public function createUser()
    {
        return view('admin.users.create');
    }

    /**
     * Guardar nuevo docente
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::min(8)],
            'role' => ['required', 'in:admin,docente'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electronico es obligatorio.',
            'email.email' => 'Ingrese un correo electronico valido.',
            'email.unique' => 'Este correo electronico ya esta registrado.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
            'role.required' => 'El rol es obligatorio.',
            'role.in' => 'El rol debe ser administrador o docente.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'active' => true,
        ]);

        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Formulario para editar usuario
     */
    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Actualizar usuario
     */
    public function updateUser(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,docente'],
            'active' => ['required', 'boolean'],
        ];

        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electronico es obligatorio.',
            'email.email' => 'Ingrese un correo electronico valido.',
            'email.unique' => 'Este correo electronico ya esta registrado.',
            'role.required' => 'El rol es obligatorio.',
        ];

        // Solo validar password si se proporciona
        if ($request->filled('password')) {
            $rules['password'] = ['min:8'];
            $messages['password.min'] = 'La contrasena debe tener al menos 8 caracteres.';
        }

        $request->validate($rules, $messages);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'active' => $request->boolean('active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Eliminar usuario
     */
    public function destroyUser(User $user)
    {
        // No permitir eliminarse a si mismo
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'No puede eliminarse a si mismo.');
        }

        // Eliminar archivos del usuario
        foreach ($user->books as $book) {
            if (Storage::exists($book->file_path)) {
                Storage::delete($book->file_path);
            }
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Listar todos los libros (admin)
     */
    public function books(Request $request)
    {
        $query = Book::with('uploader');

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('type') && in_array($request->type, ['libro', 'audiolibro'])) {
            $query->ofType($request->type);
        }

        $books = $query->latest()->paginate(10)->appends($request->query());

        return view('admin.books.index', compact('books'));
    }

    /**
     * Eliminar libro (admin)
     */
    public function destroyBook(Book $book)
    {
        $book->delete();

        return redirect()->route('admin.books')->with('success', 'Archivo eliminado exitosamente (eliminacion logica).');
    }
}
