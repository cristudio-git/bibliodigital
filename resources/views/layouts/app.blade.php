<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Biblioteca Digital') - Secundaria 7 - Olavarria</title>

    <!-- Bootstrap 5 CSS (local) -->
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons (local) -->
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --sec7-primary: #1a5276;
            --sec7-secondary: #2980b9;
            --sec7-accent: #e67e22;
            --sec7-light: #f8f9fa;
            --sec7-dark: #1c2833;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-sec7 {
            background-color: var(--sec7-primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .navbar-sec7 .navbar-brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 0.02em;
        }

        .navbar-sec7 .navbar-brand:hover {
            color: var(--sec7-accent);
        }

        .navbar-sec7 .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            transition: color 0.2s;
        }

        .navbar-sec7 .nav-link:hover,
        .navbar-sec7 .nav-link.active {
            color: #fff;
        }

        .btn-sec7 {
            background-color: var(--sec7-secondary);
            border-color: var(--sec7-secondary);
            color: #fff;
        }

        .btn-sec7:hover {
            background-color: var(--sec7-primary);
            border-color: var(--sec7-primary);
            color: #fff;
        }

        .btn-accent {
            background-color: var(--sec7-accent);
            border-color: var(--sec7-accent);
            color: #fff;
        }

        .btn-accent:hover {
            background-color: #d35400;
            border-color: #d35400;
            color: #fff;
        }

        .card-book {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .card-book:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .card-book .card-icon {
            width: 100%;
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
        }

        .card-book .card-icon.libro {
            background: linear-gradient(135deg, #1a5276, #2980b9);
            color: #fff;
        }

        .card-book .card-icon.audiolibro {
            background: linear-gradient(135deg, #e67e22, #f39c12);
            color: #fff;
        }

        .badge-libro {
            background-color: var(--sec7-secondary);
            color: #fff;
        }

        .badge-audiolibro {
            background-color: var(--sec7-accent);
            color: #fff;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--sec7-primary), var(--sec7-secondary));
            color: #fff;
            padding: 3rem 0;
        }

        .footer-sec7 {
            background-color: var(--sec7-dark);
            color: rgba(255, 255, 255, 0.7);
            padding: 1.5rem 0;
            margin-top: auto;
        }

        .sidebar-admin {
            background-color: #fff;
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        }

        .sidebar-admin .nav-link {
            color: var(--sec7-dark);
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            margin: 0.15rem 0.5rem;
            font-weight: 500;
        }

        .sidebar-admin .nav-link:hover {
            background-color: #eaf2f8;
            color: var(--sec7-secondary);
        }

        .sidebar-admin .nav-link.active {
            background-color: var(--sec7-secondary);
            color: #fff;
        }

        .stat-card {
            border-radius: 0.75rem;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .is-invalid-custom {
            border-color: #dc3545;
        }

        .invalid-feedback-custom {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .modal-upload .modal-header {
            background-color: var(--sec7-primary);
            color: #fff;
        }

        .modal-upload .btn-close {
            filter: invert(1);
        }

        .file-upload-area {
            border: 2px dashed #ccc;
            border-radius: 0.75rem;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.3s, background-color 0.3s;
        }

        .file-upload-area:hover,
        .file-upload-area.dragover {
            border-color: var(--sec7-secondary);
            background-color: #eaf2f8;
        }

        .file-upload-area.has-file {
            border-color: #28a745;
            background-color: #e8f8e8;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-sec7">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('biblioteca.index') }}">
                <i class="bi bi-book"></i>
                Biblioteca Digital SEC7
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <i class="bi bi-list text-white fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('biblioteca.index') ? 'active' : '' }}"
                           href="{{ route('biblioteca.index') }}">
                            <i class="bi bi-house-door me-1"></i>Inicio
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i>Panel Admin
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('libros.mis') }}">
                                <i class="bi bi-folder me-1"></i>Mis Archivos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                <i class="bi bi-cloud-upload me-1"></i>Subir Archivo
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <span class="dropdown-item-text text-muted small">
                                        {{ ucfirst(auth()->user()->role) }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar Sesion
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alertas globales -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Contenido principal -->
    <main class="flex-grow-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-sec7">
        <div class="container text-center">
            <p class="mb-0">Escuela Secundaria N.7 - Biblioteca Digital &copy; {{ date('Y') }}</p>
        </div>
    </footer>

    <!-- Modal de carga de archivos -->
    @auth
    <div class="modal fade modal-upload" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">
                        <i class="bi bi-cloud-upload me-2"></i>Subir Archivo a la Biblioteca
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form id="uploadForm" method="POST" action="{{ route('libros.store') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Tipo -->
                            <div class="col-md-6">
                                <label for="upload_type" class="form-label fw-semibold">
                                    Tipo <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="upload_type" name="type" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="libro">Libro</option>
                                    <option value="audiolibro">Audiolibro</option>
                                </select>
                                <div class="invalid-feedback">Seleccione el tipo de archivo.</div>
                            </div>

                            <!-- Nombre del libro -->
                            <div class="col-md-6">
                                <label for="upload_title" class="form-label fw-semibold">
                                    Nombre del Libro / Audiolibro <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="upload_title" name="title"
                                       placeholder="Ej: Don Quijote de la Mancha" required maxlength="255">
                                <div class="invalid-feedback">El nombre es obligatorio.</div>
                            </div>

                            <!-- Autor -->
                            <div class="col-md-6">
                                <label for="upload_author" class="form-label fw-semibold">
                                    Autor <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="upload_author" name="author"
                                       placeholder="Ej: Miguel de Cervantes" required maxlength="255">
                                <div class="invalid-feedback">El autor es obligatorio.</div>
                            </div>

                            <!-- Editorial -->
                            <div class="col-md-6">
                                <label for="upload_publisher" class="form-label fw-semibold">
                                    Editorial <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="upload_publisher" name="publisher"
                                       placeholder="Ej: Editorial Planeta" required maxlength="255">
                                <div class="invalid-feedback">La editorial es obligatoria.</div>
                            </div>

                            <!-- Ano de edicion -->
                            <div class="col-md-6">
                                <label for="upload_edition_year" class="form-label fw-semibold">
                                    Ano de Edicion <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control" id="upload_edition_year" name="edition_year"
                                       placeholder="Ej: 2023" required min="1800" max="{{ date('Y') }}">
                                <div class="invalid-feedback">Ingrese un ano de edicion valido.</div>
                            </div>

                            <!-- Archivo -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    Archivo <span class="text-danger">*</span>
                                </label>
                                <div class="file-upload-area" id="fileUploadArea">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                    <p class="mb-1 mt-2 text-muted">Arrastre su archivo aqui o haga clic para seleccionar</p>
                                    <small class="text-muted">
                                        Libros: PDF, EPUB, DOC, DOCX | Audiolibros: MP3, WAV, OGG, M4A
                                    </small>
                                    <p class="mb-0 mt-1 small text-muted">Tamano maximo: 100 MB</p>
                                    <input type="file" class="d-none" id="upload_archivo" name="archivo"
                                           accept=".pdf,.epub,.doc,.docx,.mp3,.wav,.ogg,.m4a,.aac,.wma" required>
                                </div>
                                <div id="fileInfo" class="mt-2 d-none">
                                    <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                        <i class="bi bi-file-earmark fs-4 text-primary"></i>
                                        <div>
                                            <strong id="fileName"></strong>
                                            <br><small class="text-muted" id="fileSize"></small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-auto" id="removeFile">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="invalid-feedback" id="fileError">Debe seleccionar un archivo.</div>
                            </div>

                            <!-- Comentarios -->
                            <div class="col-12">
                                <label for="upload_comments" class="form-label fw-semibold">
                                    Comentarios <span class="text-muted fw-normal">(opcional)</span>
                                </label>
                                <textarea class="form-control" id="upload_comments" name="comments" rows="3"
                                          placeholder="Agregue una descripcion o comentarios sobre el archivo..."
                                          maxlength="1000"></textarea>
                            </div>
                        </div>

                        <!-- Errores del servidor -->
                        @if($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sec7" id="uploadSubmitBtn">
                            <i class="bi bi-cloud-upload me-1"></i>Subir Archivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endauth

    <!-- Bootstrap 5 JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <!-- JavaScript de validacion del modal de subida -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // === Upload Modal Logic ===
        var uploadForm = document.getElementById('uploadForm');
        var fileUploadArea = document.getElementById('fileUploadArea');
        var fileInput = document.getElementById('upload_archivo');
        var fileInfo = document.getElementById('fileInfo');
        var fileNameEl = document.getElementById('fileName');
        var fileSizeEl = document.getElementById('fileSize');
        var removeFileBtn = document.getElementById('removeFile');
        var fileError = document.getElementById('fileError');

        if (!uploadForm) return;

        // Click en area de subida abre el selector de archivos
        fileUploadArea.addEventListener('click', function() {
            fileInput.click();
        });

        // Drag and drop
        fileUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        fileUploadArea.addEventListener('dragleave', function() {
            this.classList.remove('dragover');
        });

        fileUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                showFileInfo(e.dataTransfer.files[0]);
            }
        });

        // Cuando se selecciona un archivo
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                showFileInfo(this.files[0]);
            }
        });

        function showFileInfo(file) {
            fileNameEl.textContent = file.name;
            fileSizeEl.textContent = formatFileSize(file.size);
            fileInfo.classList.remove('d-none');
            fileUploadArea.classList.add('has-file');
            fileError.style.display = 'none';
        }

        function formatFileSize(bytes) {
            if (bytes >= 1073741824) return (bytes / 1073741824).toFixed(2) + ' GB';
            if (bytes >= 1048576) return (bytes / 1048576).toFixed(2) + ' MB';
            if (bytes >= 1024) return (bytes / 1024).toFixed(2) + ' KB';
            return bytes + ' bytes';
        }

        // Remover archivo seleccionado
        if (removeFileBtn) {
            removeFileBtn.addEventListener('click', function() {
                fileInput.value = '';
                fileInfo.classList.add('d-none');
                fileUploadArea.classList.remove('has-file');
            });
        }

        // Validacion del formulario de subida
        uploadForm.addEventListener('submit', function(e) {
            var isValid = true;

            // Limpiar validaciones previas
            var fields = uploadForm.querySelectorAll('.form-control, .form-select');
            fields.forEach(function(field) {
                field.classList.remove('is-invalid');
            });

            // Validar tipo
            var typeField = document.getElementById('upload_type');
            if (!typeField.value) {
                typeField.classList.add('is-invalid');
                isValid = false;
            }

            // Validar titulo
            var titleField = document.getElementById('upload_title');
            if (!titleField.value.trim()) {
                titleField.classList.add('is-invalid');
                isValid = false;
            }

            // Validar autor
            var authorField = document.getElementById('upload_author');
            if (!authorField.value.trim()) {
                authorField.classList.add('is-invalid');
                isValid = false;
            }

            // Validar editorial
            var publisherField = document.getElementById('upload_publisher');
            if (!publisherField.value.trim()) {
                publisherField.classList.add('is-invalid');
                isValid = false;
            }

            // Validar ano
            var yearField = document.getElementById('upload_edition_year');
            var yearVal = parseInt(yearField.value);
            if (!yearField.value || isNaN(yearVal) || yearVal < 1800 || yearVal > new Date().getFullYear()) {
                yearField.classList.add('is-invalid');
                isValid = false;
            }

            // Validar archivo
            if (!fileInput.files || fileInput.files.length === 0) {
                fileError.style.display = 'block';
                fileUploadArea.classList.add('is-invalid-custom');
                isValid = false;
            } else {
                // Validar tamano (100 MB)
                if (fileInput.files[0].size > 104857600) {
                    fileError.textContent = 'El archivo supera los 100 MB permitidos.';
                    fileError.style.display = 'block';
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                return;
            }

            // Deshabilitar boton para evitar doble envio
            var submitBtn = document.getElementById('uploadSubmitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Subiendo...';
        });

        // Limpiar validaciones al escribir
        uploadForm.querySelectorAll('.form-control, .form-select').forEach(function(field) {
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
            field.addEventListener('change', function() {
                this.classList.remove('is-invalid');
            });
        });

        // Abrir modal si hay errores del servidor
        @if($errors->any())
            var uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
            uploadModal.show();
        @endif
    });
    </script>

    <!-- JavaScript para manejo de sesiones y navegación -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        @auth
            // Para usuarios autenticados: prevenir volver atrás al login
            // Reemplazar la entrada actual del historial para que no se pueda volver atrás
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }

            // Prevenir que el usuario pueda volver atrás usando el botón del navegador
            window.addEventListener('beforeunload', function(e) {
                // Solo mostrar confirmación si el usuario intenta salir de la página
                // pero no si está navegando dentro del sitio
            });

            // Limpiar cualquier estado de login del historial
            if (window.history.length > 1) {
                // Si hay más de una entrada en el historial, reemplazar la anterior
                window.history.replaceState({ authenticated: true }, document.title, window.location.href);
            }
        @endauth

        @guest
            // Para usuarios no autenticados: asegurar que no haya cache
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.getRegistrations().then(function(registrations) {
                    for (let registration of registrations) {
                        registration.unregister();
                    }
                });
            }
        @endguest
    });
    </script>

    @stack('scripts')
</body>
</html>
