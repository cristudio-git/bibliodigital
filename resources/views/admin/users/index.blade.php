@extends('layouts.admin')

@section('title', 'Gestion de Usuarios')

@section('admin-content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-people me-2"></i>Gestion de Usuarios
    </h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-sec7">
        <i class="bi bi-person-plus me-1"></i>Nuevo Usuario
    </a>
</div>

<!-- Filtros -->
<div class="card shadow-sm border-0 rounded-3 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-semibold">Buscar</label>
                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                       placeholder="Nombre o correo electronico...">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Rol</label>
                <select class="form-select" name="role">
                    <option value="">Todos</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrativo</option>
                    <option value="docente" {{ request('role') === 'docente' ? 'selected' : '' }}>Docente</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-sec7">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de usuarios -->
<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-0">
        @if($users->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-people fs-1"></i>
                <p class="mt-2">No se encontraron usuarios.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th>Correo Electronico</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Archivos</th>
                            <th>Registro</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->isAdmin())
                                        <span class="badge bg-primary">Administrativo</span>
                                    @else
                                        <span class="badge bg-info text-dark">Docente</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->active)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $user->books_count }}</span>
                                </td>
                                <td><small class="text-muted">{{ $user->created_at->format('d/m/Y') }}</small></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-warning"
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <button type="button" class="btn btn-outline-danger" title="Eliminar"
                                                    onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                    @if($user->id !== auth()->id())
                                        <form id="delete-user-{{ $user->id }}" method="POST"
                                              action="{{ route('admin.users.destroy', $user) }}" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
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
    {{ $users->links() }}
</div>

<!-- Modal de confirmacion -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmar Eliminacion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Esta seguro de eliminar al usuario <strong id="deleteUserName"></strong>?</p>
                <p class="text-muted small mb-0">Se eliminaran tambien todos los archivos subidos por este usuario.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteUserBtn">
                    <i class="bi bi-trash me-1"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var deleteUserId = null;

function confirmDeleteUser(id, name) {
    deleteUserId = id;
    document.getElementById('deleteUserName').textContent = name;
    var modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    modal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    var confirmBtn = document.getElementById('confirmDeleteUserBtn');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            if (deleteUserId) {
                document.getElementById('delete-user-' + deleteUserId).submit();
            }
        });
    }
});
</script>
@endpush
