# Arquitectura de Datos - Biblioteca Digital

## Introducción

Este documento describe la arquitectura de datos del proyecto Biblioteca Digital, una aplicación web desarrollada con Laravel que permite a docentes y administradores gestionar y compartir libros y audiolibros. Como ingeniero de datos, he analizado el almacenamiento, gestión y flujo de datos para proporcionar una visión completa del sistema.

## Arquitectura General

La aplicación sigue una arquitectura MVC (Modelo-Vista-Controlador) típica de Laravel, con una base de datos MySQL para el almacenamiento persistente. Los datos se gestionan a través de modelos Eloquent, controladores y middleware, con vistas Blade para la presentación.

### Tecnologías Principales
- **Framework**: Laravel 11.x
- **Base de Datos**: MySQL 8.x
- **ORM**: Eloquent
- **Autenticación**: Laravel Sanctum (para APIs futuras)
- **Almacenamiento de Archivos**: Sistema de archivos local (storage/app/libros)

## Almacenamiento de Datos

### Base de Datos

La aplicación utiliza una base de datos MySQL llamada `biblioteca_sec7` con las siguientes tablas principales:

#### 1. Tabla `users`
Almacena información de usuarios (docentes y administradores).

**Esquema:**
- `id` (BIGINT, PRIMARY KEY, AUTO_INCREMENT)
- `name` (VARCHAR(255))
- `email` (VARCHAR(255), UNIQUE)
- `email_verified_at` (TIMESTAMP, NULLABLE)
- `password` (VARCHAR(255), HASHED)
- `role` (ENUM: 'admin', 'docente', DEFAULT 'docente')
- `active` (BOOLEAN, DEFAULT TRUE)
- `remember_token` (VARCHAR(100), NULLABLE)
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

**Índices:** Ninguno adicional.
- `idx_users_role_active_created (role, active, created_at)` para filtrar/paginar docentes/admin activos en vistas de administración.

#### 2. Tabla `books`
Almacena información de los libros y audiolibros subidos.

**Esquema:**
- `id` (BIGINT, PRIMARY KEY, AUTO_INCREMENT)
- `title` (VARCHAR(255))
- `author` (VARCHAR(255))
- `publisher` (VARCHAR(255))
- `edition_year` (YEAR)
- `comments` (TEXT, NULLABLE)
- `type` (ENUM: 'libro', 'audiolibro', DEFAULT 'libro')
- `file_name` (VARCHAR(255))
- `file_path` (VARCHAR(255))
- `file_mime` (VARCHAR(255))
- `file_size` (BIGINT UNSIGNED)
- `uploaded_by` (BIGINT UNSIGNED, FOREIGN KEY → users.id, ON DELETE CASCADE)
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

**Índices:**
- `type` (INDEX)
- `uploaded_by` (INDEX)
- `idx_books_uploaded_by_deleted_created (uploaded_by, deleted_at, created_at)` para acelerar panel "Mis libros" (filtra por usuario, excluye soft deletes y ordena por fecha).
- `idx_books_type_deleted_created (type, deleted_at, created_at)` para listar/buscar por tipo manteniendo orden cronologico con soft deletes.

#### 3. Tabla `password_reset_tokens`
Gestiona tokens para recuperación de contraseña.

**Esquema:**
- `email` (VARCHAR(255), PRIMARY KEY)
- `token` (VARCHAR(255))
- `created_at` (TIMESTAMP, NULLABLE)

#### 4. Tabla `sessions`
Almacena sesiones de usuario para autenticación.

**Esquema:**
- `id` (VARCHAR(255), PRIMARY KEY)
- `user_id` (BIGINT UNSIGNED, NULLABLE, INDEX, FOREIGN KEY → users.id)
- `ip_address` (VARCHAR(45), NULLABLE)
- `user_agent` (TEXT, NULLABLE)
- `payload` (LONGTEXT)
- `last_activity` (INT, INDEX)

### Almacenamiento de Archivos

Los archivos de libros se almacenan en el sistema de archivos local:
- **Directorio**: `storage/app/libros/`
- **Estructura**: Archivos organizados por ID de libro
- **Tipos soportados**: PDF, EPUB, MP3, etc. (según MIME type)
- **Acceso**: A través de enlaces de descarga protegidos

### Configuración de Base de Datos

```php
// config/database.php
'default' => env('DB_CONNECTION', 'mysql'),
'connections' => [
    'mysql' => [
        'database' => env('DB_DATABASE', 'biblioteca_sec7'),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ]
]
```

## Gestión de Datos

### Modelos Eloquent

#### User Model
- **Relaciones**: `hasMany(Book::class, 'uploaded_by')`
- **Métodos**: `isAdmin()` para verificar rol de administrador
- **Fillable**: name, email, password, role, active
- **Hidden**: password, remember_token
- **Casts**: email_verified_at (datetime), password (hashed), active (boolean)

#### Book Model
- **Relaciones**: `belongsTo(User::class, 'uploaded_by')` (uploader)
- **Scopes**:
  - `ofType(string $type)`: Filtrar por tipo (libro/audiolibro)
  - `search(?string $search)`: Búsqueda en title, author, publisher
- **Métodos**: `getFormattedSizeAttribute()` para tamaño formateado
- **Casts**: edition_year (integer), file_size (integer)

### Validación y Reglas de Negocio

#### StoreBookRequest
Valida la subida de libros:
- title: required, string, max:255
- author: required, string, max:255
- publisher: required, string, max:255
- edition_year: required, integer, min:1000, max:current_year
- comments: nullable, string
- type: required, in:libro,audiolibro
- file: required, file, mimes:pdf,epub,mp3,wav, max:50MB

#### UpdateBookRequest
Similar pero sin requerir file.

### Políticas de Acceso
- **Lectura**: Todos los usuarios pueden ver libros
- **Escritura**: Solo usuarios autenticados pueden subir libros
- **Edición**: Usuarios pueden editar sus propios libros; admins pueden editar todos
- **Eliminación**: No implementada (solo admins podrían tener acceso)

## Flujo de Datos

### 1. Registro y Autenticación
```
Usuario → Formulario Login → AuthController::login()
    ↓
Validación de credenciales
    ↓
Sesión creada en tabla 'sessions'
    ↓
Redirección a dashboard
```

### 2. Subida de Libros
```
Usuario autenticado → Formulario subida → BookController::store()
    ↓
Validación (StoreBookRequest)
    ↓
Archivo subido a storage/app/libros/
    ↓
Registro creado en tabla 'books'
    ↓
Redirección con mensaje de éxito
```

### 3. Consulta de Libros
```
Usuario → Página biblioteca → BookController::index()
    ↓
Query con filtros (tipo, búsqueda, ordenamiento)
    ↓
Paginación (12 libros por página)
    ↓
Vista renderizada con datos
```

### 4. Descarga de Libros
```
Usuario → Enlace descarga → BookController::download()
    ↓
Verificación de existencia del archivo
    ↓
Stream del archivo con headers apropiados
```

### 5. Edición de Libros
```
Usuario autorizado → Formulario edición → BookController::update()
    ↓
Validación (UpdateBookRequest)
    ↓
Actualización en tabla 'books'
    ↓
Redirección con mensaje de éxito
```

### 6. Recuperación de Contraseña
```
Usuario → Formulario reset → AuthController::sendResetLink()
    ↓
Token generado y guardado en 'password_reset_tokens'
    ↓
Email enviado con enlace de reset
    ↓
Usuario → Enlace → AuthController::resetPassword()
    ↓
Contraseña actualizada en 'users'
```

## Consideraciones de Seguridad

### Autenticación
- Uso de bcrypt para hashing de contraseñas
- Sesiones seguras con tokens remember
- Protección CSRF en formularios

### Autorización
- Middleware 'auth' para rutas protegidas
- Verificación de roles (admin vs docente)
- Control de acceso basado en propiedad (solo editar libros propios)

### Validación de Archivos
- Límites de tamaño (50MB máximo)
- Tipos MIME permitidos
- Almacenamiento seguro fuera del directorio público

## Rendimiento y Optimización

### Índices de Base de Datos
- `books.type` para filtrado rápido por tipo
- `books.uploaded_by` para consultas de libros por usuario
- `idx_books_uploaded_by_deleted_created (uploaded_by, deleted_at, created_at)` para panel "Mis libros"
- `idx_books_type_deleted_created (type, deleted_at, created_at)` para listados por tipo con soft deletes
- `idx_users_role_active_created (role, active, created_at)` para filtros en administración de usuarios
- `sessions.user_id` y `last_activity` para gestión de sesiones

### Caché (Redis recomendado)
- Driver sugerido: `CACHE_DRIVER=redis` para evitar bloqueo de archivos y soportar concurrencia.
- Clave de versión `books:version` se incrementa al crear/editar/eliminar/restaurar libros; invalida cachés dependientes.
- Consultas cacheadas 5 minutos:
  - Biblioteca pública (`BookController@index`): clave `books:index:v{version}:type:{type}:search:{search}:sort:{sort}:page:{page}`.
  - Dashboard admin (`AdminController@dashboard`): clave `admin:dashboard:v{version}`.
- Funciona también con `file` cache, aunque se recomienda Redis en producción.

### Carga Eficiente
- Eager loading con `Book::with('uploader')`
- Paginación para listas grandes
- Scopes para consultas reutilizables

### Almacenamiento de Archivos
- Archivos organizados por ID para acceso rápido
- Metadata almacenada en BD para búsquedas
- Streaming para descargas grandes

## Monitoreo y Mantenimiento

### Logs
- Logs de aplicación en `storage/logs/`
- Logs de base de datos configurables

### Backups
- Recomendado backup regular de BD y archivos
- Estrategia: mysqldump + rsync de storage/

### Migraciones
- Versionado de esquema con Laravel migrations
- Rollback posible con `php artisan migrate:rollback`

## Conclusiones y Recomendaciones

La arquitectura de datos es sólida y escalable para una aplicación de biblioteca digital. Las recomendaciones incluyen:

1. **Implementar soft deletes** para libros eliminados
2. **Agregar índices compuestos** para búsquedas complejas
3. **Considerar cache** (Redis) para consultas frecuentes
4. **Implementar auditoría** de cambios en libros
5. **Agregar validación de integridad** de archivos
6. **Considerar particionamiento** si crece mucho la tabla books

Esta documentación debe mantenerse actualizada con cualquier cambio en el esquema o lógica de datos.
