@extends('layouts.admin')

@section('title', 'Panel de Administracion')

@section('admin-content')
<h4 class="fw-bold mb-4">
    <i class="bi bi-speedometer2 me-2"></i>Panel de Administracion
</h4>

<!-- Estadisticas -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background-color: #eaf2f8; color: var(--sec7-secondary);">
                    <i class="bi bi-collection"></i>
                </div>
                <div>
                    <div class="small text-muted">Total Archivos</div>
                    <div class="fs-4 fw-bold">{{ $stats['total_books'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background-color: #e8f5e9; color: #2e7d32;">
                    <i class="bi bi-book"></i>
                </div>
                <div>
                    <div class="small text-muted">Libros</div>
                    <div class="fs-4 fw-bold">{{ $stats['total_libros'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background-color: #fff3e0; color: var(--sec7-accent);">
                    <i class="bi bi-headphones"></i>
                </div>
                <div>
                    <div class="small text-muted">Audiolibros</div>
                    <div class="fs-4 fw-bold">{{ $stats['total_audiolibros'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background-color: #fce4ec; color: #c62828;">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="small text-muted">Usuarios</div>
                    <div class="fs-4 fw-bold">{{ $stats['total_users'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ultimos archivos -->
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-bold mb-0">Ultimos archivos subidos</h6>
        <a href="{{ route('admin.books') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
    </div>
    <div class="card-body p-0">
        @if($recentBooks->isEmpty())
            <div class="text-center py-4 text-muted">
                <i class="bi bi-inbox fs-3"></i>
                <p class="mt-2 mb-0">No hay archivos todavia.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tipo</th>
                            <th>Titulo</th>
                            <th>Autor</th>
                            <th>Subido por</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBooks as $book)
                            <tr>
                                <td>
                                    <span class="badge badge-{{ $book->type }}">
                                        {{ $book->type === 'libro' ? 'Libro' : 'Audio' }}
                                    </span>
                                </td>
                                <td class="fw-semibold">{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>
                                    <small>{{ $book->uploader->name ?? 'N/A' }}</small>
                                </td>
                                <td><small class="text-muted">{{ $book->created_at->format('d/m/Y H:i') }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
