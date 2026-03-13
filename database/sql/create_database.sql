-- ================================================================
--  BIBLIOTECA DIGITAL - ESCUELA SECUNDARIA N.7 (SEC7)
--  Script completo de creacion de base de datos
-- ================================================================
--  Motor  : MariaDB 10.5+ / MySQL 8.0+
--  Charset: utf8mb4 (soporte completo de emojis y acentos)
--
--  INSTRUCCIONES:
--    1. Abri tu cliente de MariaDB/MySQL (phpMyAdmin, HeidiSQL,
--       DBeaver, terminal, etc.)
--    2. Copia y pega TODO este script y ejecutalo.
--    3. Si ya existe la base de datos, el script la borra y la
--       recrea de cero (ver linea DROP DATABASE).
--       Si no queres eso, comenta esa linea.
--
--  USUARIO ADMIN CREADO AUTOMATICAMENTE:
--    Email   : admin@sec7.edu.ar
--    Password: sec7admin2024
--    IMPORTANTE: Cambia la contrasena despues del primer login.
-- ================================================================


-- ----------------------------------------------------------------
-- 1. CREAR LA BASE DE DATOS
-- ----------------------------------------------------------------
-- Si queres conservar datos existentes, comenta la linea DROP.
DROP DATABASE IF EXISTS biblioteca_sec7;

CREATE DATABASE biblioteca_sec7
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE biblioteca_sec7;


-- ----------------------------------------------------------------
-- 2. TABLA: users
--    Almacena bibliotecarias (admin) y docentes.
--    El campo 'role' controla los permisos en la aplicacion.
-- ----------------------------------------------------------------
CREATE TABLE users (
    id              BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    name            VARCHAR(255)     NOT NULL,
    email           VARCHAR(255)     NOT NULL,
    email_verified_at TIMESTAMP      NULL DEFAULT NULL,
    password        VARCHAR(255)     NOT NULL,
    role            ENUM('admin', 'docente') NOT NULL DEFAULT 'docente'
                    COMMENT 'admin = bibliotecaria, docente = profesor',
    active          TINYINT(1)       NOT NULL DEFAULT 1
                    COMMENT '1 = activo, 0 = deshabilitado',
    remember_token  VARCHAR(100)     NULL DEFAULT NULL,
    created_at      TIMESTAMP        NULL DEFAULT NULL,
    updated_at      TIMESTAMP        NULL DEFAULT NULL,

    PRIMARY KEY (id),
    UNIQUE KEY uk_users_email (email),
    INDEX idx_users_role_active_created (role, active, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Usuarios del sistema (bibliotecarias y docentes)';


-- ----------------------------------------------------------------
-- 3. TABLA: password_reset_tokens
--    Tokens temporales para recuperacion de contrasena.
-- ----------------------------------------------------------------
CREATE TABLE password_reset_tokens (
    email       VARCHAR(255) NOT NULL,
    token       VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP    NULL DEFAULT NULL,

    PRIMARY KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tokens de recuperacion de contrasena';


-- ----------------------------------------------------------------
-- 4. TABLA: books
--    Almacena libros y audiolibros subidos al sistema.
--    El campo 'type' distingue entre libro y audiolibro.
--    'uploaded_by' referencia al usuario que subio el archivo.
-- ----------------------------------------------------------------
CREATE TABLE books (
    id              BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
    title           VARCHAR(255)     NOT NULL
                    COMMENT 'Nombre del libro o audiolibro',
    author          VARCHAR(255)     NOT NULL
                    COMMENT 'Autor de la obra',
    publisher       VARCHAR(255)     NOT NULL
                    COMMENT 'Editorial',
    edition_year    YEAR             NOT NULL
                    COMMENT 'Ano de edicion',
    comments        TEXT             NULL DEFAULT NULL
                    COMMENT 'Comentarios opcionales',
    type            ENUM('libro', 'audiolibro') NOT NULL DEFAULT 'libro'
                    COMMENT 'Tipo de recurso',
    file_name       VARCHAR(255)     NOT NULL
                    COMMENT 'Nombre original del archivo subido',
    file_path       VARCHAR(500)     NOT NULL
                    COMMENT 'Ruta de almacenamiento en el servidor',
    file_mime       VARCHAR(255)     NOT NULL
                    COMMENT 'Tipo MIME del archivo (application/pdf, audio/mpeg, etc.)',
    file_size       BIGINT UNSIGNED  NOT NULL
                    COMMENT 'Tamano del archivo en bytes',
    uploaded_by     BIGINT UNSIGNED  NOT NULL
                    COMMENT 'ID del usuario que subio el archivo',
    download_count  INT UNSIGNED     NOT NULL DEFAULT 0
                    COMMENT 'Cantidad de descargas',
    created_at      TIMESTAMP        NULL DEFAULT NULL,
    updated_at      TIMESTAMP        NULL DEFAULT NULL,
    deleted_at      TIMESTAMP        NULL DEFAULT NULL,

    PRIMARY KEY (id),
    INDEX idx_books_type (type),
    INDEX idx_books_uploaded_by (uploaded_by),
    INDEX idx_books_uploaded_by_deleted_created (uploaded_by, deleted_at, created_at),
    INDEX idx_books_type_deleted_created (type, deleted_at, created_at),
    INDEX idx_books_title (title),
    INDEX idx_books_author (author),

    CONSTRAINT fk_books_uploaded_by
        FOREIGN KEY (uploaded_by)
        REFERENCES users (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Catalogo de libros y audiolibros de la biblioteca';


-- ----------------------------------------------------------------
-- 5. TABLA: sessions
--    Manejo de sesiones de usuario almacenadas en base de datos.
--    Requerida por Laravel cuando SESSION_DRIVER=database.
-- ----------------------------------------------------------------
CREATE TABLE sessions (
    id              VARCHAR(255)     NOT NULL,
    user_id         BIGINT UNSIGNED  NULL DEFAULT NULL,
    ip_address      VARCHAR(45)      NULL DEFAULT NULL,
    user_agent      TEXT             NULL DEFAULT NULL,
    payload         LONGTEXT         NOT NULL,
    last_activity   INT              NOT NULL,

    PRIMARY KEY (id),
    INDEX idx_sessions_user_id (user_id),
    INDEX idx_sessions_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Sesiones activas de usuarios';


-- ----------------------------------------------------------------
-- 6. TABLA: migrations
--    Tabla interna de Laravel para llevar registro de las
--    migraciones ejecutadas. Se crea automaticamente pero
--    la incluimos por si se ejecuta solo este SQL.
-- ----------------------------------------------------------------
CREATE TABLE migrations (
    id          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    migration   VARCHAR(255)     NOT NULL,
    batch       INT              NOT NULL,

    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Registro interno de migraciones de Laravel';


-- ----------------------------------------------------------------
-- 7. REGISTRAR LAS MIGRACIONES COMO EJECUTADAS
--    Para que Laravel sepa que las tablas ya existen y no intente
--    crearlas de nuevo al ejecutar "php artisan migrate".
-- ----------------------------------------------------------------
INSERT INTO migrations (migration, batch) VALUES
    ('2024_01_01_000001_create_users_table', 1),
    ('2024_01_01_000002_create_password_reset_tokens_table', 1),
    ('2024_01_01_000003_create_books_table', 1),
    ('2024_01_01_000004_create_sessions_table', 1);


-- ----------------------------------------------------------------
-- 8. INSERTAR USUARIO ADMINISTRADOR POR DEFECTO
-- ----------------------------------------------------------------
--  Email   : admin@sec7.edu.ar
--  Password: sec7admin2024
--
--  El hash de abajo fue generado con bcrypt (costo 12).
--  Si por alguna razon no funciona el login con esta contrasena,
--  ejecuta en la consola de Laravel:
--    php artisan tinker
--    > echo Hash::make('sec7admin2024');
--  Y reemplaza el valor en la columna password de este usuario.
-- ----------------------------------------------------------------
INSERT INTO users (name, email, password, role, active, created_at, updated_at)
VALUES (
    'Bibliotecaria SEC7',
    'admin@sec7.edu.ar',
    '$2y$12$LJ3m4oLPYFTSB6gUwMOs0OGBsRkEF/5.Ys5phmaKnFllP/uBiCzG2',
    'admin',
    1,
    NOW(),
    NOW()
);


-- ----------------------------------------------------------------
-- 9. VERIFICACION - Mostrar las tablas creadas
-- ----------------------------------------------------------------
SELECT '--- TABLAS CREADAS EXITOSAMENTE ---' AS mensaje;
SHOW TABLES;

SELECT '--- USUARIO ADMIN CREADO ---' AS mensaje;
SELECT id, name, email, role, active FROM users WHERE role = 'admin';


-- ================================================================
--  FIN DEL SCRIPT
--
--  Resumen de tablas creadas:
--    - users                 : Bibliotecarias y docentes
--    - password_reset_tokens : Recuperacion de contrasena
--    - books                 : Catalogo de libros y audiolibros
--    - sessions              : Sesiones de usuario
--    - migrations            : Registro interno de Laravel
--
--  Proximo paso:
--    Configura el archivo .env del proyecto Laravel con los
--    datos de conexion a esta base de datos:
--      DB_CONNECTION=mysql
--      DB_HOST=127.0.0.1
--      DB_PORT=3306
--      DB_DATABASE=biblioteca_sec7
--      DB_USERNAME=tu_usuario
--      DB_PASSWORD=tu_contrasena
-- ================================================================
