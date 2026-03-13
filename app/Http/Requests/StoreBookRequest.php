<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['required', 'string', 'max:255'],
            'edition_year' => ['required', 'integer', 'min:1800', 'max:' . date('Y')],
            'comments' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', 'in:libro,audiolibro'],
            'archivo' => ['required', 'file', 'max:2048', 'mimes:pdf,epub,doc,docx,mp3,wav,ogg,m4a,aac,wma'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El nombre del libro es obligatorio.',
            'title.max' => 'El nombre no puede superar los 255 caracteres.',
            'author.required' => 'El autor es obligatorio.',
            'author.max' => 'El autor no puede superar los 255 caracteres.',
            'publisher.required' => 'La editorial es obligatoria.',
            'publisher.max' => 'La editorial no puede superar los 255 caracteres.',
            'edition_year.required' => 'El ano de edicion es obligatorio.',
            'edition_year.integer' => 'El ano de edicion debe ser un numero.',
            'edition_year.min' => 'El ano de edicion no puede ser anterior a 1800.',
            'edition_year.max' => 'El ano de edicion no puede ser posterior al ano actual.',
            'type.required' => 'Debe seleccionar si es un libro o audiolibro.',
            'type.in' => 'El tipo debe ser libro o audiolibro.',
            'archivo.required' => 'Debe seleccionar un archivo.',
            'archivo.file' => 'El archivo no es valido.',
            'archivo.max' => 'El archivo no puede superar los 2 MB.',
            'archivo.mimes' => 'Formatos permitidos: PDF, EPUB, DOC, DOCX, MP3, WAV, OGG, M4A, AAC, WMA.',
        ];
    }
}
