@extends('layouts.admin')

@section('title', 'Editar Usuario: ' . $user->name)

@section('admin-content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h4 class="fw-bold mb-0">Editar Usuario</h4>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                Nombre Completo <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">
                                Correo Electronico <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">
                                Nueva Contrasena <span class="text-muted fw-normal">(dejar vacio para no cambiar)</span>
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" placeholder="Minimo 8 caracteres">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="role" class="form-label fw-semibold">
                                Rol <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="docente" {{ old('role', $user->role) === 'docente' ? 'selected' : '' }}>Docente</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrativo</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="active" class="form-label fw-semibold">
                                Estado <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('active') is-invalid @enderror" id="active" name="active" required>
                                <option value="1" {{ old('active', $user->active) ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !old('active', $user->active) ? 'selected' : '' }}>Inactivo</option>
                            </select>
                            @error('active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Info adicional -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Registrado el:</small><br>
                                <strong>{{ $user->created_at->format('d/m/Y H:i') }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Archivos subidos:</small><br>
                                <strong>{{ $user->books()->count() }}</strong>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Ultimo acceso:</small><br>
                                <strong>{{ $user->updated_at->format('d/m/Y H:i') }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-sec7">
                            <i class="bi bi-check-lg me-1"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
