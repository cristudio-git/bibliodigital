@extends('layouts.app')

@section('content')
<div class="admin-wrapper">

    {{-- Cabecera institucional del panel --}}
    <div class="admin-header-bar">
        <div class="admin-header-inner">
            <div class="admin-identity">
                {{-- Botón hamburguesa — solo visible en mobile --}}
                <button class="admin-hamburger d-md-none" id="adminDrawerToggle" aria-label="Abrir menú">
                    <i class="bi bi-list"></i>
                </button>
                <div class="admin-escudo">
                    <i class="bi bi-building"></i>
                </div>
                <div class="admin-identity-text">
                    <span class="admin-school-name">Escuela Secundaria N.° 7 — Olavarría</span>
                    <span class="admin-panel-label">Panel de Administración</span>
                </div>
            </div>
            <div class="admin-user-info">
                <i class="bi bi-person-badge me-1"></i>
                <span>{{ auth()->user()->name }}</span>
                <span class="admin-role-badge">Admin</span>
            </div>
        </div>
    </div>

    <div class="admin-body">

        {{-- Overlay para cerrar el drawer en mobile --}}
        <div class="admin-drawer-overlay" id="adminDrawerOverlay"></div>

        {{-- Sidebar --}}
        <aside class="admin-sidebar" id="adminSidebar">

            {{-- Cabecera interna del drawer (visible solo en mobile) --}}
            <div class="admin-sidebar-drawer-header">
                <span class="admin-sidebar-drawer-title">Menú</span>
                <button class="admin-drawer-close" id="adminDrawerClose" aria-label="Cerrar menú">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <nav class="admin-nav">
                <div class="admin-nav-section-label">Gestión</div>
                <ul class="admin-nav-list">
                    <li>
                        <a class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}"
                           href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="admin-nav-link {{ request()->routeIs('admin.books*') ? 'is-active' : '' }}"
                           href="{{ route('admin.books') }}">
                            <i class="bi bi-journals"></i>
                            <span>Libros y Audiolibros</span>
                        </a>
                    </li>
                    <li>
                        <a class="admin-nav-link {{ request()->routeIs('admin.users*') ? 'is-active' : '' }}"
                           href="{{ route('admin.users') }}">
                            <i class="bi bi-people"></i>
                            <span>Usuarios</span>
                        </a>
                    </li>
                </ul>

                <div class="admin-nav-divider"></div>

                <div class="admin-nav-section-label">Accesos</div>
                <ul class="admin-nav-list">
                    <li>
                        <a class="admin-nav-link" href="{{ route('biblioteca.index') }}">
                            <i class="bi bi-box-arrow-left"></i>
                            <span>Volver a Biblioteca</span>
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="admin-nav-link admin-nav-logout w-100 text-start border-0 bg-transparent">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>

            {{-- Pie del sidebar --}}
            <div class="admin-sidebar-footer">
                <i class="bi bi-shield-lock me-1"></i>
                Acceso restringido
            </div>
        </aside>

        {{-- Contenido principal --}}
        <main class="admin-main">
            @yield('admin-content')
        </main>

    </div>
</div>
@endsection

@push('styles')
<style>
    /* =============================================
       PANEL ADMIN — IDENTIDAD INSTITUCIONAL SEC7
       ============================================= */

    /* Reset del body para el panel */
    body:has(.admin-wrapper) {
        background-color: #eef1f5;
    }

    /* Ocultar el navbar global en el panel admin */
    body:has(.admin-wrapper) > nav.navbar-sec7 {
        display: none;
    }

    /* Wrapper principal */
    .admin-wrapper {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    /* -----------------------------------------------
       CABECERA INSTITUCIONAL
    ----------------------------------------------- */
    .admin-header-bar {
        background: linear-gradient(90deg, #0d3b5e 0%, #1a5276 60%, #1f618d 100%);
        border-bottom: 3px solid #e67e22;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .admin-header-inner {
        max-width: 100%;
        padding: 0 1.5rem;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .admin-identity {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .admin-escudo {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.12);
        border: 1.5px solid rgba(255,255,255,0.25);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #fff;
        flex-shrink: 0;
    }

    .admin-identity-text {
        display: flex;
        flex-direction: column;
        line-height: 1.2;
    }

    .admin-school-name {
        color: #fff;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.01em;
    }

    .admin-panel-label {
        color: rgba(255,255,255,0.6);
        font-size: 0.72rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-weight: 500;
        margin-top: 1px;
    }

    .admin-user-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255,255,255,0.85);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .admin-role-badge {
        background: #e67e22;
        color: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 2px 8px;
        border-radius: 20px;
    }

    /* -----------------------------------------------
       CUERPO: SIDEBAR + MAIN
    ----------------------------------------------- */
    .admin-body {
        display: flex;
        flex: 1;
        min-height: 0;
    }

    /* -----------------------------------------------
       SIDEBAR
    ----------------------------------------------- */
    .admin-sidebar {
        width: 240px;
        flex-shrink: 0;
        background: #fff;
        border-right: 1px solid #dde3ea;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: calc(100vh - 64px);
        position: sticky;
        top: 64px;
        height: calc(100vh - 64px);
        overflow-y: auto;
    }

    .admin-nav {
        padding: 1.25rem 0.75rem 1rem;
    }

    .admin-nav-section-label {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #8a96a3;
        padding: 0 0.75rem;
        margin-bottom: 0.35rem;
    }

    .admin-nav-list {
        list-style: none;
        padding: 0;
        margin: 0 0 0.5rem;
    }

    .admin-nav-link {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        padding: 0.6rem 0.75rem;
        border-radius: 7px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #3d4a58;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
        cursor: pointer;
        line-height: 1.4;
    }

    .admin-nav-link i {
        font-size: 1rem;
        width: 18px;
        text-align: center;
        flex-shrink: 0;
        color: #7a8694;
        transition: color 0.15s;
    }

    .admin-nav-link:hover {
        background: #eef4fb;
        color: #1a5276;
    }

    .admin-nav-link:hover i {
        color: #1a5276;
    }

    .admin-nav-link.is-active {
        background: #1a5276;
        color: #fff;
        font-weight: 600;
    }

    .admin-nav-link.is-active i {
        color: #fff;
    }

    .admin-nav-link.admin-nav-logout {
        color: #b04028;
    }

    .admin-nav-link.admin-nav-logout i {
        color: #b04028;
    }

    .admin-nav-link.admin-nav-logout:hover {
        background: #fdf0ed;
        color: #922c18;
    }

    .admin-nav-divider {
        height: 1px;
        background: #e8ecf0;
        margin: 0.75rem 0.75rem 0.875rem;
    }

    .admin-sidebar-footer {
        padding: 0.875rem 1.25rem;
        border-top: 1px solid #e8ecf0;
        font-size: 0.72rem;
        color: #9aa4af;
        font-weight: 500;
        letter-spacing: 0.02em;
        display: flex;
        align-items: center;
    }

    /* -----------------------------------------------
       CONTENIDO PRINCIPAL
    ----------------------------------------------- */
    .admin-main {
        flex: 1;
        min-width: 0;
        padding: 2rem 2.25rem;
        overflow-y: auto;
    }

    /* -----------------------------------------------
       HAMBURGUESA
    ----------------------------------------------- */
    .admin-hamburger {
        display: none;
        background: rgba(255,255,255,0.12);
        border: 1.5px solid rgba(255,255,255,0.25);
        border-radius: 7px;
        color: #fff;
        width: 38px;
        height: 38px;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        cursor: pointer;
        flex-shrink: 0;
        transition: background 0.15s;
    }

    .admin-hamburger:hover {
        background: rgba(255,255,255,0.22);
    }

    /* -----------------------------------------------
       OVERLAY
    ----------------------------------------------- */
    .admin-drawer-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 199;
        opacity: 0;
        transition: opacity 0.25s;
    }

    .admin-drawer-overlay.is-visible {
        display: block;
        opacity: 1;
    }

    /* -----------------------------------------------
       RESPONSIVE — DRAWER LATERAL
    ----------------------------------------------- */
    @media (max-width: 768px) {

        .admin-hamburger {
            display: flex;
        }

        .admin-school-name {
            font-size: 0.82rem;
        }

        .admin-panel-label {
            display: none;
        }

        .admin-main {
            padding: 1.25rem 1rem;
        }

        /* Sidebar fuera de pantalla por defecto */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            min-height: 100vh;
            z-index: 200;
            transform: translateX(-100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: none;
            width: 270px;
        }

        /* Drawer abierto */
        .admin-sidebar.is-open {
            transform: translateX(0);
            box-shadow: 4px 0 24px rgba(0,0,0,0.18);
        }

        /* Cabecera dentro del drawer */
        .admin-sidebar-drawer-header {
            display: flex !important;
        }
    }

    /* -----------------------------------------------
       COMPONENTES PARA LAS VISTAS ADMIN
    ----------------------------------------------- */

    /* Título de sección */
    .admin-page-header {
        margin-bottom: 1.75rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #dde3ea;
    }

    .admin-page-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0d3b5e;
        margin: 0 0 0.2rem;
        letter-spacing: -0.01em;
    }

    .admin-page-subtitle {
        font-size: 0.875rem;
        color: #6b7a8a;
        margin: 0;
    }

    /* Tarjetas de estadísticas */
    .admin-stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .admin-stat-card {
        background: #fff;
        border: 1px solid #dde3ea;
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .admin-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .admin-stat-icon.primary { background: #e8f0f8; color: #1a5276; }
    .admin-stat-icon.accent  { background: #fdf0e6; color: #c0611a; }
    .admin-stat-icon.success { background: #e8f5ee; color: #1e7a45; }
    .admin-stat-icon.muted   { background: #f0f2f5; color: #5a6474; }

    .admin-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #0d3b5e;
        line-height: 1;
        margin-bottom: 2px;
    }

    .admin-stat-label {
        font-size: 0.78rem;
        color: #7a8694;
        font-weight: 500;
        letter-spacing: 0.02em;
    }

    /* Tabla admin */
    .admin-table-card {
        background: #fff;
        border: 1px solid #dde3ea;
        border-radius: 10px;
        overflow: hidden;
    }

    .admin-table-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #dde3ea;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .admin-table-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a3a52;
        margin: 0;
    }

    .admin-table {
        width: 100%;
        margin: 0;
        font-size: 0.875rem;
    }

    .admin-table thead th {
        background: #f5f7fa;
        color: #5a6474;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #dde3ea;
        border-top: none;
        white-space: nowrap;
    }

    .admin-table tbody td {
        padding: 0.875rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f0f2f5;
        color: #2d3a47;
    }

    .admin-table tbody tr:last-child td {
        border-bottom: none;
    }

    .admin-table tbody tr:hover td {
        background: #f8fafc;
    }

    /* Botones admin */
    .btn-admin-primary {
        background: #1a5276;
        border-color: #1a5276;
        color: #fff;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.45rem 1rem;
        border-radius: 7px;
        transition: background 0.15s, border-color 0.15s;
    }

    .btn-admin-primary:hover {
        background: #0d3b5e;
        border-color: #0d3b5e;
        color: #fff;
    }

    .btn-admin-danger {
        background: #c0392b;
        border-color: #c0392b;
        color: #fff;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.45rem 1rem;
        border-radius: 7px;
        transition: background 0.15s;
    }

    .btn-admin-danger:hover {
        background: #922c22;
        border-color: #922c22;
        color: #fff;
    }

    /* Footer del panel */
    body:has(.admin-wrapper) footer.footer-sec7 {
        display: none;
    }

    /* Cabecera interna del drawer (solo mobile) */
    .admin-sidebar-drawer-header {
        display: none;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1rem 0.5rem 1.25rem;
        border-bottom: 1px solid #e8ecf0;
        margin-bottom: 0.5rem;
    }

    .admin-sidebar-drawer-title {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        color: #1a5276;
    }

    .admin-drawer-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #7a8694;
        cursor: pointer;
        padding: 4px 6px;
        border-radius: 5px;
        line-height: 1;
        transition: background 0.15s, color 0.15s;
    }

    .admin-drawer-close:hover {
        background: #f0f2f5;
        color: #1a5276;
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var toggle  = document.getElementById('adminDrawerToggle');
    var sidebar = document.getElementById('adminSidebar');
    var overlay = document.getElementById('adminDrawerOverlay');

    if (!toggle || !sidebar || !overlay) return;

    function openDrawer() {
        sidebar.classList.add('is-open');
        overlay.classList.add('is-visible');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        sidebar.classList.remove('is-open');
        overlay.classList.remove('is-visible');
        document.body.style.overflow = '';
    }

    toggle.addEventListener('click', openDrawer);
    overlay.addEventListener('click', closeDrawer);

    var closeBtn = document.getElementById('adminDrawerClose');
    if (closeBtn) closeBtn.addEventListener('click', closeDrawer);

    // Cerrar con Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeDrawer();
    });

    // Cerrar al navegar (si se hace clic en un link del sidebar en mobile)
    sidebar.querySelectorAll('a.admin-nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 768) closeDrawer();
        });
    });
})();
</script>
@endpush