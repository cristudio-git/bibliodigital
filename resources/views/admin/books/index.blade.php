@extends('layouts.admin')

@section('title', 'Gestion de Archivos')

@section('admin-content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-book me-2"></i>Gestion de Archivos
    </h4>
    <button class="btn btn-sec7" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-cloud-upload me-1"></i>Subir Archivo
    </button>
</div>

<!-- Filtros -->
<div class="card shadow-sm border-0 rounded-3 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.books') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold">Buscar</label>
                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                       placeholder="Titulo, autor o editorial...">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Tipo</label>
                <select class="form-select" name="type">
                    <option value="">Todos</option>
                    <option value="libro" {{ request('type') === 'libro' ? 'selected' : '' }}>Libros</option>
                    <option value="audiolibro" {{ request('type') === 'audiolibro' ? 'selected' : '' }}>Audiolibros</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-sec7">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
                <a href="{{ route('admin.books') }}" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de libros -->
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-0">
        @if($books->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">No se encontraron archivos.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Titulo</th>
                            <th>Autor</th>
                            <th>Editorial</th>
                            <th>Ano</th>
                            <th>Subido por</th>
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
                                <td>
                                    <small>
                                        {{ $book->uploader->name ?? 'N/A' }}
                                        <br>
                                        <span class="text-muted">{{ ucfirst($book->uploader->role ?? '') }}</span>
                                    </small>
                                </td>
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
                                        <button type="button" class="btn btn-outline-danger" title="Eliminar"
                                                onclick="confirmDelete({{ $book->id }}, '{{ addslashes($book->title) }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-book-{{ $book->id }}" method="POST"
                                          action="{{ route('admin.books.destroy', $book) }}" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $books->links() }}
</div>

<!-- Modal de confirmacion de eliminacion -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmar Eliminacion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Esta seguro de eliminar <strong id="deleteBookTitle"></strong>?</p>
                <p class="text-muted small mb-0">Esta accion no se puede deshacer. El archivo sera eliminado permanentemente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="bi bi-trash me-1"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var deleteBookId = null;

function confirmDelete(id, title) {
    deleteBookId = id;
    document.getElementById('deleteBookTitle').textContent = title;
    var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    var confirmBtn = document.getElementById('confirmDeleteBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (deleteBookId) {
                document.getElementById('delete-book-' + deleteBookId).submit();
            }
        });
    }
});
</script>
@endpush
