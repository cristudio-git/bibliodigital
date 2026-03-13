@extends('layouts.app')

@section('title', 'Pagina No Encontrada')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <i class="bi bi-question-circle fs-1 text-warning"></i>
        <h2 class="mt-3 fw-bold">Pagina No Encontrada</h2>
        <p class="text-muted">La pagina que busca no existe o fue eliminada.</p>
        <a href="{{ route('biblioteca.index') }}" class="btn btn-sec7">
            <i class="bi bi-house-door me-1"></i>Volver a la Biblioteca
        </a>
    </div>
</div>
@endsection
