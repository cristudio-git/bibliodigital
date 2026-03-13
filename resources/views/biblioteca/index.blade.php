@extends('layouts.app')

@section('title', 'Biblioteca Digital')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-3">Biblioteca Digital</h1>
        <p class="lead mb-4" style="max-width: 600px; margin: 0 auto;">
            Accede a libros y audiolibros de la Escuela Secundaria N.7.
            Descarga lo que necesites de forma libre y gratuita.
        </p>

        <!-- Buscador -->
        <form method="GET" action="{{ route('biblioteca.index') }}" class="row g-2 justify-content-center" style="max-width: 700px; margin: 0 auto;">
            <div class="col-sm-7">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" name="search"
                           value="{{ request('search') }}" placeholder="Buscar por titulo, autor o editorial...">
                </div>
            </div>
            <div class="col-sm-3">
                <select class="form-select form-select-lg" name="type">
                    <option value="">Todos</option>
                    <option value="libro" {{ request('type') === 'libro' ? 'selected' : '' }}>Libros</option>
                    <option value="audiolibro" {{ request('type') === 'audiolibro' ? 'selected' : '' }}>Audiolibros</option>
                </select>
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-accent btn-lg w-100">Buscar</button>
            </div>
        </form>
    </div>
</section>

<!-- Contenido -->
<section class="container py-4">
    <!-- Filtros y ordenamiento -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">
                @if(request('search'))
                    Resultados para "{{ request('search') }}"
                @elseif(request('type') === 'libro')
                    Libros
                @elseif(request('type') === 'audiolibro')
                    Audiolibros
                @else
                    Todos los archivos
                @endif
                <span class="badge bg-secondary ms-2">{{ $books->total() }}</span>
            </h5>
        </div>
        <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
            <label class="small text-muted mb-0">Ordenar:</label>
            <select class="form-select form-select-sm" style="width: auto;" id="sortSelect">
                <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Mas recientes</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Mas antiguos</option>
                <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Titulo A-Z</option>
                <option value="author" {{ request('sort') === 'author' ? 'selected' : '' }}>Autor A-Z</option>
            </select>
        </div>
    </div>

    @if($books->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <h5 class="mt-3 text-muted">No se encontraron archivos</h5>
            <p class="text-muted">
                @if(request('search'))
                    Intente con otros terminos de busqueda.
                @else
                    La biblioteca esta vacia por el momento.
                @endif
            </p>
            @if(request('search'))
                <a href="{{ route('biblioteca.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Ver todos los archivos
                </a>
            @endif
        </div>
    @else
        <!-- Grilla de libros -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($books as $book)
                <div class="col">
                    <div class="card card-book h-100 shadow-sm">
                        <div class="card-icon {{ $book->type }}">
                            @if($book->isAudiobook())
                                <i class="bi bi-headphones"></i>
                            @else
                                <i class="bi bi-book"></i>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge badge-{{ $book->type }} small">
                                    {{ $book->type === 'libro' ? 'Libro' : 'Audiolibro' }}
                                </span>
                                <small class="text-muted">{{ $book->edition_year }}</small>
                            </div>
                            <h6 class="card-title fw-bold mb-1">{{ $book->title }}</h6>
                            <p class="card-text small text-muted mb-1">
                                <i class="bi bi-person me-1"></i>{{ $book->author }}
                            </p>
                            <p class="card-text small text-muted mb-2">
                                <i class="bi bi-building me-1"></i>{{ $book->publisher }}
                            </p>
                            @if($book->comments)
                                <p class="card-text small text-muted mb-2 flex-grow-1" style="max-height: 60px; overflow: hidden;">
                                    {{ $book->comments }}
                                </p>
                            @else
                                <div class="flex-grow-1"></div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                <small class="text-muted">{{ $book->formatted_size }}</small>
                                <a href="{{ route('libros.descargar', $book) }}" class="btn btn-sm btn-sec7">
                                    <i class="bi bi-download me-1"></i>Descargar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginacion -->
        <div class="d-flex justify-content-center mt-4">
            {{ $books->links() }}
        </div>
    @endif
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var sortSelect = document.getElementById('sortSelect');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            var url = new URL(window.location.href);
            url.searchParams.set('sort', this.value);
            url.searchParams.delete('page');
            window.location.href = url.toString();
        });
    }
});
</script>
@endpush
