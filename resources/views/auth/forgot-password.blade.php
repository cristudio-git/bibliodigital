@extends('layouts.app')

@section('title', 'Recuperar Contrasena')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-key fs-1" style="color: var(--sec7-accent);"></i>
                        <h3 class="mt-2 fw-bold" style="color: var(--sec7-primary);">Recuperar Contrasena</h3>
                        <p class="text-muted small">
                            Ingrese su correo electronico y le enviaremos un enlace para restablecer su contrasena.
                        </p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle me-1"></i>{{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Correo Electronico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}"
                                       placeholder="correo@ejemplo.com" required autofocus>
                            </div>
                            @error('email')
                                <div class="invalid-feedback-custom">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-sec7 w-100 py-2 fw-semibold">
                            <i class="bi bi-send me-1"></i>Enviar Enlace de Recuperacion
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesion
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
