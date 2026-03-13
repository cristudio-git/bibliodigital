@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar-admin py-3">
            <div class="px-3 mb-3">
                <h6 class="text-uppercase text-muted small fw-bold letter-spacing-1">Administracion</h6>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.books*') ? 'active' : '' }}"
                       href="{{ route('admin.books') }}">
                        <i class="bi bi-book me-2"></i>Libros y Audiolibros
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                       href="{{ route('admin.users') }}">
                        <i class="bi bi-people me-2"></i>Usuarios
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link" href="{{ route('biblioteca.index') }}">
                        <i class="bi bi-arrow-left me-2"></i>Volver a Biblioteca
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            @yield('admin-content')
        </main>
    </div>
</div>
@endsection
