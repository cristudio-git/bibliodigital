<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'edition_year',
        'comments',
        'type',
        'file_name',
        'file_path',
        'file_mime',
        'file_size',
        'uploaded_by',
    ];

    protected $casts = [
        'edition_year' => 'integer',
        'file_size' => 'integer',
    ];

    /**
     * Usuario que subio el libro
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Obtener el tamano formateado del archivo
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }

    /**
     * Verificar si es un audiolibro
     */
    public function isAudiobook(): bool
    {
        return $this->type === 'audiolibro';
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope para busqueda
     */
    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('publisher', 'LIKE', "%{$search}%");
            });
        }
        return $query;
    }

    protected static function booted(): void
    {
        $bump = fn () => self::bumpCacheVersion();

        static::created($bump);
        static::updated($bump);
        static::deleted($bump);
        static::restored($bump);
    }

    private static function bumpCacheVersion(): void
    {
        // Versionamiento simple para invalidar caches sin depender de tags (file/redis/memcached)
        Cache::increment('books:version');
        Cache::rememberForever('books:version', fn () => 1);
    }
}
