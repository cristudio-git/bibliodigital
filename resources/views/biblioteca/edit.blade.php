@extends('layouts.app')

@section('title', 'Editar: ' . $book->title)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-3">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-bold mb-0">Editar Archivo</h4>
            </div>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('libros.update', $book) }}" enctype="multipart/form-data" novalidate id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Tipo -->
                            <div class="col-md-6">
                                <label for="type" class="form-label fw-semibold">
                                    Tipo <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="libro" {{ old('type', $book->type) === 'libro' ? 'selected' : '' }}>Libro</option>
                                    <option value="audiolibro" {{ old('type', $book->type) === 'audiolibro' ? 'selected' : '' }}>Audiolibro</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Titulo -->
                            <div class="col-md-6">
                                <label for="title" class="form-label fw-semibold">
                                    Nombre <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title', $book->title) }}" required maxlength="255">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Autor -->
                            <div class="col-md-6">
                                <label for="author" class="form-label fw-semibold">
                                    Autor <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror"
                                       id="author" name="author" value="{{ old('author', $book->author) }}" required maxlength="255">
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Editorial -->
                            <div class="col-md-6">
                                <label for="publisher" class="form-label fw-semibold">
                                    Editorial <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('publisher') is-invalid @enderror"
                                       id="publisher" name="publisher" value="{{ old('publisher', $book->publisher) }}" required maxlength="255">
                                @error('publisher')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Ano de edicion -->
                            <div class="col-md-6">
                                <label for="edition_year" class="form-label fw-semibold">
                                    Ano de Edicion <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('edition_year') is-invalid @enderror"
                                       id="edition_year" name="edition_year"
                                       value="{{ old('edition_year', $book->edition_year) }}" required min="1800" max="{{ date('Y') }}">
                                @error('edition_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Archivo actual -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Archivo Actual</label>
                                <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                    <i class="bi bi-file-earmark fs-4 text-primary"></i>
                                    <div>
                                        <strong>{{ $book->file_name }}</strong>
                                        <br><small class="text-muted">{{ $book->formatted_size }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Nuevo archivo (opcional) -->
                            <div class="col-12">
                                <label for="archivo" class="form-label fw-semibold">
                                    Reemplazar Archivo <span class="text-muted fw-normal">(opcional)</span>
                                </label>
                                <input type="file" class="form-control @error('archivo') is-invalid @enderror"
                                       id="archivo" name="archivo"
                                       accept=".pdf,.epub,.doc,.docx,.mp3,.wav,.ogg,.m4a,.aac,.wma">
                                <small class="text-muted">Deje vacio para mantener el archivo actual. Max: 100 MB</small>
                                @error('archivo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Comentarios -->
                            <div class="col-12">
                                <label for="comments" class="form-label fw-semibold">
                                    Comentarios <span class="text-muted fw-normal">(opcional)</span>
                                </label>
                                <textarea class="form-control @error('comments') is-invalid @enderror"
                                          id="comments" name="comments" rows="3"
                                          maxlength="1000">{{ old('comments', $book->comments) }}</textarea>
                                @error('comments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-sec7">
                                <i class="bi bi-check-lg me-1"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
