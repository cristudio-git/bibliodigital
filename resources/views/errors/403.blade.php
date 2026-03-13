@extends('layouts.app')

@section('title', 'Acceso Denegado')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <i class="bi bi-shield-x fs-1 text-danger"></i>
        <h2 class="mt-3 fw-bold">Acceso Denegado</h2>
        <p class="text-muted">No tiene permiso para acceder a esta seccion.</p>
        <a href="{{ route('biblioteca.index') }}" class="btn btn-sec7">
            <i class="bi bi-house-door me-1"></i>Volver a la Biblioteca
        </a>
    </div>
</div>
@endsection
