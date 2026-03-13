@extends('layouts.app')

@section('title', 'Iniciar Sesion')

@php
    // Headers para prevenir cache de la página de login
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
@endphp

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <i class="bi bi-book fs-1" style="color: var(--sec7-primary);"></i>
                        <h3 class="mt-2 fw-bold" style="color: var(--sec7-primary);">Biblioteca Digital</h3>
                        <p class="text-muted">Escuela Secundaria N.7</p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" novalidate>
                        @csrf

                        <div class="mb-3">
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

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Contrasena</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" placeholder="Su contrasena" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback-custom">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="remember">Recordarme</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="small text-decoration-none"
                               style="color: var(--sec7-secondary);">
                                Olvide mi contrasena
                            </a>
                        </div>

                        <button type="submit" class="btn btn-sec7 w-100 py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesion
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('biblioteca.index') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i>Volver a la biblioteca
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var toggleBtn = document.getElementById('togglePassword');
    var passwordField = document.getElementById('password');

    if (toggleBtn && passwordField) {
        toggleBtn.addEventListener('click', function() {
            var type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            var icon = this.querySelector('i');
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    }

    // Prevenir cache del navegador para esta página
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations().then(function(registrations) {
            for (let registration of registrations) {
                registration.unregister();
            }
        });
    }

    // Limpiar historial si hay parámetros de éxito en la URL
    if (window.location.search.includes('success') || window.location.search.includes('login')) {
        // Reemplazar la entrada actual del historial para prevenir volver atrás
        window.history.replaceState(null, null, window.location.pathname);
    }
});
</script>
@endpush
