<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookController extends Controller
{
    /**
     * Pagina publica de la biblioteca - accesible por todos
     */
    public function index(Request $request)
    {
        $cacheVersion = Cache::get('books:version', 1);
        $page = max((int)$request->get('page', 1), 1);
        $cacheKey = sprintf(
            'books:index:v%s:type:%s:search:%s:sort:%s:page:%d',
            $cacheVersion,
            $request->get('type', ''),
            $request->get('search', ''),
            $request->get('sort', 'newest'),
            $page
        );

        $books = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($request) {
            $query = Book::with('uploader');

            // Filtro por tipo
            if ($request->filled('type') && in_array($request->type, ['libro', 'audiolibro'])) {
                $query->ofType($request->type);
            }

            // Busqueda
            if ($request->filled('search')) {
                $query->search($request->search);
            }

            // Ordenamiento
            $sort = $request->get('sort', 'newest');
            switch ($sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'title':
                    $query->orderBy('title', 'asc');
                    break;
                case 'author':
                    $query->orderBy('author', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            return $query->paginate(12)->appends($request->query());
        });

        return view('biblioteca.index', compact('books'));
    }

    /**
     * Guardar un nuevo libro/audiolibro
     */
    public function store(StoreBookRequest $request)
    {
        $file = $request->file('archivo');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('libros', $fileName);

        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'edition_year' => $request->edition_year,
            'comments' => $request->comments,
            'type' => $request->type,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_mime' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'El archivo fue cargado exitosamente.');
    }

    /**
     * Descargar un libro - accesible por todos
     */
    public function download(Book $book): StreamedResponse
    {
        if (!Storage::exists($book->file_path)) {
            abort(404, 'El archivo no fue encontrado.');
        }

        return Storage::download($book->file_path, $book->file_name);
    }

    /**
     * Mostrar formulario de edicion
     */
    public function edit(Book $book)
    {
        $user = Auth::user();

        // Un docente solo puede editar sus propios libros
        if ($user->isDocente() && $book->uploaded_by !== $user->id) {
            abort(403, 'No tiene permiso para editar este archivo.');
        }

        return view('biblioteca.edit', compact('book'));
    }

    /**
     * Actualizar un libro
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $user = Auth::user();

        // Un docente solo puede editar sus propios libros
        if ($user->isDocente() && $book->uploaded_by !== $user->id) {
            abort(403, 'No tiene permiso para editar este archivo.');
        }

        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'edition_year' => $request->edition_year,
            'comments' => $request->comments,
            'type' => $request->type,
        ];

        // Si se sube un nuevo archivo, reemplazar el anterior
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior
            if (Storage::exists($book->file_path)) {
                Storage::delete($book->file_path);
            }

            $file = $request->file('archivo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('libros', $fileName);

            $data['file_name'] = $file->getClientOriginalName();
            $data['file_path'] = $filePath;
            $data['file_mime'] = $file->getClientMimeType();
            $data['file_size'] = $file->getSize();
        }

        $book->update($data);

        return redirect()->route('libros.mis')->with('success', 'El archivo fue actualizado exitosamente.');
    }

    /**
     * Mis libros - panel del docente/admin
     */
    public function myBooks(Request $request)
    {
        $user = Auth::user();
        $query = $user->books()->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $books = $query->paginate(10);

        return view('biblioteca.my-books', compact('books'));
    }
}
