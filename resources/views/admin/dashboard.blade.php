@extends('layouts.admin')

@section('title', 'Dashboard — Admin')

@section('admin-content')

{{-- Encabezado de página --}}
<div class="admin-page-header">
    <h1 class="admin-page-title">Dashboard</h1>
    <p class="admin-page-subtitle">Resumen general de la Biblioteca Digital — {{ now()->format('d/m/Y') }}</p>
</div>

{{-- Tarjetas de estadísticas --}}
<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon primary">
            <i class="bi bi-journals"></i>
        </div>
        <div>
            <div class="admin-stat-value">{{ $totalBooks ?? 0 }}</div>
            <div class="admin-stat-label">Libros totales</div>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon accent">
            <i class="bi bi-headphones"></i>
        </div>
        <div>
            <div class="admin-stat-value">{{ $totalAudiobooks ?? 0 }}</div>
            <div class="admin-stat-label">Audiolibros</div>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon success">
            <i class="bi bi-people"></i>
        </div>
        <div>
            <div class="admin-stat-value">{{ $totalUsers ?? 0 }}</div>
            <div class="admin-stat-label">Docentes activos</div>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon muted">
            <i class="bi bi-calendar3"></i>
        </div>
        <div>
            <div class="admin-stat-value">{{ $recentBooks ?? 0 }}</div>
            <div class="admin-stat-label">Subidos este mes</div>
        </div>
    </div>
</div>

{{-- Tabla de últimos archivos subidos --}}
<div class="admin-table-card">
    <div class="admin-table-card-header">
        <h2 class="admin-table-card-title">
            <i class="bi bi-clock-history me-2 text-muted" style="font-size:.9rem"></i>
            Últimos archivos subidos
        </h2>
        <a href="{{ route('admin.books') }}" class="btn btn-admin-primary btn-sm">
            Ver todos
        </a>
    </div>

    <div class="table-responsive">
        <table class="admin-table table mb-0">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Tipo</th>
                    <th>Subido por</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBooksList ?? [] as $book)
                <tr>
                    <td>
                        <span class="fw-semibold" style="color:#0d3b5e">{{ $book->title }}</span>
                        <br>
                        <small class="text-muted">{{ $book->publisher }} · {{ $book->edition_year }}</small>
                    </td>
                    <td>{{ $book->author }}</td>
                    <td>
                        @if($book->type === 'libro')
                            <span class="badge" style="background:#e8f0f8;color:#1a5276;font-weight:600;font-size:.7rem">
                                <i class="bi bi-book me-1"></i>Libro
                            </span>
                        @else
                            <span class="badge" style="background:#fdf0e6;color:#c0611a;font-weight:600;font-size:.7rem">
                                <i class="bi bi-headphones me-1"></i>Audiolibro
                            </span>
                        @endif
                    </td>
                    <td>{{ $book->uploader->name ?? '—' }}</td>
                    <td style="white-space:nowrap;color:#7a8694">
                        {{ $book->created_at->format('d/m/Y') }}
                    </td>
                    <td style="white-space:nowrap">
                        <a href="{{ route('libros.edit', $book) }}"
                           class="btn btn-sm btn-outline-secondary"
                           style="font-size:.75rem;padding:.3rem .65rem;border-radius:6px">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4" style="font-size:.875rem">
                        <i class="bi bi-inbox fs-4 d-block mb-2 opacity-50"></i>
                        No hay archivos subidos aún.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection