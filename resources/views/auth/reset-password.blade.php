@extends('layouts.app')

@section('title', 'Restablecer Contrasena')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock fs-1" style="color: var(--sec7-accent);"></i>
                        <h3 class="mt-2 fw-bold" style="color: var(--sec7-primary);">Nueva Contrasena</h3>
                        <p class="text-muted small">Ingrese su nueva contrasena.</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Correo Electronico</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', request()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Nueva Contrasena</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required placeholder="Minimo 8 caracteres">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirmar Contrasena</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                   name="password_confirmation" required placeholder="Repita la contrasena">
                        </div>

                        <button type="submit" class="btn btn-sec7 w-100 py-2 fw-semibold">
                            <i class="bi bi-check-lg me-1"></i>Restablecer Contrasena
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
