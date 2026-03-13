@extends('layouts.app')

@section('title', 'Mis Archivos')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <i class="bi bi-folder me-2" style="color: var(--sec7-primary);"></i>Mis Archivos
        </h4>
        <button class="btn btn-sec7" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-cloud-upload me-1"></i>Subir Archivo
        </button>
    </div>

    <!-- Buscador -->
    <form method="GET" action="{{ route('libros.mis') }}" class="mb-4">
        <div class="input-group" style="max-width: 400px;">
            <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                   placeholder="Buscar en mis archivos...">
            <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

    @if($books->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-cloud-upload fs-1 text-muted"></i>
            <h5 class="mt-3 text-muted">No tiene archivos subidos</h5>
            <p class="text-muted">Suba su primer libro o audiolibro a la biblioteca.</p>
            <button class="btn btn-sec7" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-cloud-upload me-1"></i>Subir Archivo
            </button>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle bg-white rounded shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>Tipo</th>
                        <th>Titulo</th>
                        <th>Autor</th>
                        <th>Editorial</th>
                        <th>Ano</th>
                        <th>Tamano</th>
                        <th>Fecha</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td>
                                <span class="badge badge-{{ $book->type }}">
                                    <i class="bi bi-{{ $book->isAudiobook() ? 'headphones' : 'book' }} me-1"></i>
                                    {{ $book->type === 'libro' ? 'Libro' : 'Audio' }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->publisher }}</td>
                            <td>{{ $book->edition_year }}</td>
                            <td><small class="text-muted">{{ $book->formatted_size }}</small></td>
                            <td><small class="text-muted">{{ $book->created_at->format('d/m/Y') }}</small></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('libros.descargar', $book) }}" class="btn btn-outline-primary"
                                       title="Descargar">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    <a href="{{ route('libros.edit', $book) }}" class="btn btn-outline-warning"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $books->links() }}
        </div>
    @endif
</div>
@endsection
