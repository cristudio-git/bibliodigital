# 📚 Guía Completa de Despliegue - Biblioteca Digital

## Índice
1. [Requisitos Previos](#requisitos-previos)
2. [Preparación del Servidor](#preparación-del-servidor)
3. [Instalación del Proyecto](#instalación-del-proyecto)
4. [Configuración del Entorno](#configuración-del-entorno)
5. [Configuración de la Base de Datos](#configuración-de-la-base-de-datos)
6. [Configuración del Servidor Web](#configuración-del-servidor-web)
7. [Compilación de Assets](#compilación-de-assets)
8. [Configuración de Almacenamiento](#configuración-de-almacenamiento)
9. [Configuración de Correo](#configuración-de-correo)
10. [Seguridad y Rendimiento](#seguridad-y-rendimiento)
11. [Verificación Final](#verificación-final)
12. [Mantenimiento](#mantenimiento)

---

## Requisitos Previos

### Mínimos del Sistema
- **PHP**: versión 8.1 o superior
- **MariaDB/MySQL**: 10.4+ / 8.0+
- **Servidor Web**: Apache (mod_rewrite) o Nginx
- **Composer**: gestor de dependencias PHP
- **Node.js**: v14+ (para compilar assets con Vite)
- **Git**: para clonar el repositorio (opcional)

### Extensiones PHP Requeridas
```
bcmath, ctype, fileinfo, json, mbstring, 
openssl, pdo, pdo_mysql, tokenizer, xml
```

### Verificar Requisitos
```bash
# Verificar PHP
php -v

# Verificar extensiones PHP
php -m | grep -E "bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|tokenizer|xml"

# Verificar Composer
composer --version

# Verificar Node.js y npm
node --version && npm --version

# Verificar MySQL/MariaDB
mysql --version
```

---

## Preparación del Servidor

### En Linux (Ubuntu/Debian)

#### Instalar dependencias base
```bash
sudo apt update
sudo apt upgrade -y
sudo apt install -y php php-cli php-common php-mysql php-bcmath php-ctype php-fileinfo php-json php-mbstring php-openssl php-pdo php-tokenizer php-xml php-curl php-fpm
```

#### Instalar Composer
```bash
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
```

#### Instalar Node.js y npm
```bash
# Opción 1: NodeSource repository (recomendado)
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install -y nodejs

# Opción 2: nvm (Node Version Manager)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
nvm install lts/*
```

#### Instalar y configurar MariaDB
```bash
sudo apt install -y mariadb-server
sudo mysql_secure_installation

# Iniciar el servicio
sudo systemctl start mariadb
sudo systemctl enable mariadb
```

#### Instalar Apache o Nginx
```bash
# Apache (recomendado para principiantes)
sudo apt install -y apache2
sudo a2enmod rewrite
sudo a2enmod ssl
sudo systemctl start apache2
sudo systemctl enable apache2

# O Nginx (mejor rendimiento)
sudo apt install -y nginx
sudo systemctl start nginx
sudo systemctl enable nginx
```

---

## Instalación del Proyecto

### 1. Clonar o Descargar el Proyecto
```bash
# Opción A: Usando Git
git clone <repositorio-url> /var/www/bibliodigital
cd /var/www/bibliodigital

# Opción B: Descargar ZIP
unzip bibliodigital.zip -d /var/www/
cd /var/www/bibliodigital

# Opción C: Si ya existe en el servidor, navegar al directorio
cd /ruta/del/proyecto
```

### 2. Establecer Permisos
```bash
# Dar permisos al usuario web (www-data en Apache)
sudo chown -R www-data:www-data /var/www/bibliodigital
sudo chmod -R 755 /var/www/bibliodigital
```

### 3. Instalar Dependencias PHP
```bash
cd /var/www/bibliodigital
composer install --optimize-autoloader --no-dev
```

### 4. Instalar Dependencias Node.js
```bash
npm install
```

---

## Configuración del Entorno

### 1. Crear archivo .env
```bash
cp .env.example .env
```

### 2. Generar Clave de Aplicación
```bash
php artisan key:generate
```

### 3. Configurar .env
Editar `/var/www/bibliodigital/.env`:

```env
APP_NAME="Biblioteca Digital"
APP_ENV=production
APP_KEY=base64:XXXX... (generado automáticamente)
APP_DEBUG=false
APP_URL=https://tubiblio.ejemplo.com

# Base de Datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblioteca_sec7
DB_USERNAME=bibliouser
DB_PASSWORD=contraseña_segura_aquí

# Cache
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

# O si no tienes Redis
CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=database

# Correo (configurar después)
MAIL_MAILER=smtp
MAIL_HOST=smtp.tu-proveedor.com
MAIL_PORT=587
MAIL_USERNAME=tu-correo@ejemplo.com
MAIL_PASSWORD=tu-contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ejemplo.com
MAIL_FROM_NAME="Biblioteca Digital"
```

---

## Configuración de la Base de Datos

### 1. Crear Usuario MySQL
```bash
sudo mysql -u root
```

En la consola MySQL:
```sql
CREATE DATABASE biblioteca_sec7 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'bibliouser'@'localhost' IDENTIFIED BY 'contraseña_segura_aquí';
GRANT ALL PRIVILEGES ON biblioteca_sec7.* TO 'bibliouser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2. Ejecutar Migraciones
```bash
cd /var/www/bibliodigital
php artisan migrate --force

# O con seeding para crear data de prueba
php artisan migrate --seed --force
```

### 3. Crear Usuario Administrador
```bash
php artisan db:seed --class=AdminSeeder
```

Verifica que el usuario admin se creó correctamente.

---

## Configuración del Servidor Web

### Opción A: Apache

#### Crear Virtual Host
Crear archivo `/etc/apache2/sites-available/bibliodigital.conf`:

```apache
<VirtualHost *:80>
    ServerName ejemplo.com
    ServerAlias www.ejemplo.com
    ServerAdmin admin@ejemplo.com
    
    DocumentRoot /var/www/bibliodigital/public
    
    <Directory /var/www/bibliodigital/public>
        AllowOverride All
        Require all granted
        
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^ index.php [QSA,L]
        </IfModule>
    </Directory>
    
    <Directory /var/www/bibliodigital>
        <IfModule mod_rewrite.c>
            RewriteEngine Off
        </IfModule>
    </Directory>
    
    # Logs
    ErrorLog ${APACHE_LOG_DIR}/bibliodigital-error.log
    CustomLog ${APACHE_LOG_DIR}/bibliodigital-access.log combined
    
    # Compresión
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/xml text/javascript application/javascript
    </IfModule>
</VirtualHost>
```

#### Habilitar el sitio y módulos
```bash
sudo a2ensite bibliodigital
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod deflate
sudo apache2ctl configtest  # Debe mostrar "Syntax OK"
sudo systemctl reload apache2
```

### Opción B: Nginx

#### Crear archivo de configuración
Crear archivo `/etc/nginx/sites-available/bibliodigital`:

```nginx
upstream php {
    server unix:/var/run/php/php8.1-fpm.sock;
}

server {
    listen 80;
    listen [::]:80;
    
    server_name ejemplo.com www.ejemplo.com;
    
    root /var/www/bibliodigital/public;
    index index.php;
    
    # Gzip compression
    gzip on;
    gzip_types text/html text/plain text/xml text/css text/javascript application/javascript application/json;
    
    # Logs
    error_log /var/log/nginx/bibliodigital-error.log;
    access_log /var/log/nginx/bibliodigital-access.log;
    
    # Seguridad
    client_max_body_size 100M;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_intercept_errors on;
    }
    
    # Archivos estáticos - no pasar a PHP
    location ~ /\.ht {
        deny all;
    }
    
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

#### Habilitar el sitio
```bash
sudo ln -s /etc/nginx/sites-available/bibliodigital /etc/nginx/sites-enabled/
sudo nginx -t  # Verificar sintaxis
sudo systemctl reload nginx
```

---

## Compilación de Assets

### 1. Compilar Assets para Producción
```bash
cd /var/www/bibliodigital
npm run build
```

Esto compilará:
- CSS desde `resources/css/app.css`
- JavaScript desde `resources/js/app.js`
- Los assets compilados irán a `public/build/`

### 2. Verificar que manifest.json existe
```bash
ls -la public/build/manifest.json
```

---

## Configuración de Almacenamiento

### 1. Crear enlace simbólico
```bash
cd /var/www/bibliodigital
php artisan storage:link
```

Esto crea: `public/storage` → `storage/app/public`

### 2. Configurar Permisos
```bash
# Carpeta storage must be writable
sudo chmod -R 775 /var/www/bibliodigital/storage
sudo chmod -R 775 /var/www/bibliodigital/bootstrap/cache

# Asegurar pertenencia
sudo chown -R www-data:www-data /var/www/bibliodigital/storage
sudo chown -R www-data:www-data /var/www/bibliodigital/bootstrap/cache
```

### 3. Crear subdirectorios si no existen
```bash
mkdir -p /var/www/bibliodigital/storage/app/libros
mkdir -p /var/www/bibliodigital/storage/app/private
chmod -R 775 /var/www/bibliodigital/storage/app
```

---

## Configuración de Correo

### Opción A: Usar Gmail (SMTP)

1. Activar "Verificación en 2 Pasos" en tu cuenta Google
2. Generar "Contraseña de Aplicación" en Google Account
3. Configurar en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-correo@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicacion
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu-correo@gmail.com
MAIL_FROM_NAME="Biblioteca Digital"
```

### Opción B: Usar Servidor Local (Postfix)

```bash
sudo apt install -y postfix
sudo systemctl start postfix

# En .env
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=25
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@ejemplo.com
MAIL_FROM_NAME="Biblioteca Digital"
```

### Opción C: Usar SendGrid, Mailgun, etc.

Seguir documentación de cada proveedor y configurar credenciales en `.env`.

### Probar Configuración de Correo
```bash
php artisan tinker
# Dentro de tinker:
>>> Mail::raw('Test de correo', function($message) { $message->to('tu@email.com'); });
```

---

## SSL/HTTPS (Muy Importante)

### Usando Let's Encrypt con Certbot

```bash
# Instalar Certbot
sudo apt install -y certbot python3-certbot-apache
# O para Nginx
sudo apt install -y certbot python3-certbot-nginx

# Generar certificado
sudo certbot certonly --apache -d ejemplo.com -d www.ejemplo.com
# O para Nginx
sudo certbot certonly --nginx -d ejemplo.com -d www.ejemplo.com

# Renovación automática
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

### Actualizar Virtual Host de Apache
```apache
<VirtualHost *:443>
    ServerName ejemplo.com
    ServerAlias www.ejemplo.com
    
    DocumentRoot /var/www/bibliodigital/public
    
    # ... resto de la configuración ...
    
    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/ejemplo.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/ejemplo.com/privkey.pem
</VirtualHost>

# Redirigir HTTP a HTTPS
<VirtualHost *:80>
    ServerName ejemplo.com
    ServerAlias www.ejemplo.com
    Redirect / https://ejemplo.com/
</VirtualHost>
```

---

## Seguridad y Rendimiento

### 1. Optimizar Autoloader
```bash
composer install --optimize-autoloader --no-dev
```

### 2. Cachear Configuración
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Configurar Headers de Seguridad
Agregar a `.env`:
```env
APP_DEBUG=false
SECURE_HEADERS_ENABLED=true
```

O manualmente en Apache/Nginx:
```apache
# Apache
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
Header set Referrer-Policy "strict-origin-when-cross-origin"
```

### 4. Limpieza de Logs
```bash
# Rotar logs automáticamente (agregar a crontab)
0 0 * * 0 php /var/www/bibliodigital/artisan tinker < <<'EOF'
File::delete(glob(storage_path('logs/laravel-*.log')));
exit;
EOF
```

### 5. Esconder Información de PHP
Editar `/etc/php/8.1/fpm/php.ini` (o apache2/php.ini):
```ini
expose_php = Off
```

---

## Verificación Final

### Checklist de Despliegue

```bash
# 1. Verificar que .env está configurado correctamente
cat /var/www/bibliodigital/.env | grep APP_

# 2. Verificar base de datos
php artisan migrate:status

# 3. Verificar que los assets están compilados
ls -la /var/www/bibliodigital/public/build/manifest.json

# 4. Verificar permisos
ls -ld /var/www/bibliodigital/storage
ls -ld /var/www/bibliodigital/bootstrap/cache

# 5. Verificar enlace de almacenamiento
ls -la /var/www/bibliodigital/public/storage

# 6. Probar que Laravel se ejecuta correctamente
php artisan tinker
>>> echo "Test";
>>> exit();

# 7. Verificar logs de error
tail -f /var/log/apache2/bibliodigital-error.log
# O para Nginx
tail -f /var/log/nginx/bibliodigital-error.log
```

### Pruebas en el Navegador
1. Ir a `https://ejemplo.com`
2. Debería mostrar la página de bienvenida
3. Intentar acceder al login (`/login`)
4. Intentar crear una sesión
5. Verificar que los assets se cargan (CSS, JS, imágenes)

---

## Mantenimiento

### Comandos Útiles

```bash
# Ver estado del servidor
php artisan serve

# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ejecutar migrations nuevas (después de actualizaciones)
php artisan migrate --force

# Ejecutar seeders
php artisan db:seed

# Generar backups de BD
mysqldump -u bibliouser -p biblioteca_sec7 > backup_$(date +%Y%m%d).sql

# Monitorear logs en tiempo real
tail -f /var/www/bibliodigital/storage/logs/laravel.log
```

### Configurar Cron para Tareas Automáticas

Agregar a crontab:
```bash
sudo crontab -e -u www-data
```

Agregar:
```cron
* * * * * php /var/www/bibliodigital/artisan schedule:run >> /dev/null 2>&1
```

### Backup Automático

Script: `/var/www/bibliodigital/backup.sh`
```bash
#!/bin/bash
BACKUP_DIR="/backups/biblioteca"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Backup de base de datos
mysqldump -u bibliouser -p[contraseña] biblioteca_sec7 > $BACKUP_DIR/bd_$DATE.sql

# Backup de archivos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/bibliodigital/storage

# Eliminar backups antiguos (30 días)
find $BACKUP_DIR -type f -mtime +30 -delete
```

Agregar a crontab:
```cron
0 2 * * * bash /var/www/bibliodigital/backup.sh
```

---

## Troubleshooting Común

| Problema | Solución |
|----------|----------|
| Error 403 (Forbidden) | Verificar permisos de carpeta `public`, archivo `.htaccess` |
| Error 500 (Internal Server Error) | Revisar `storage/logs/laravel.log`, verificar permisos de `storage/` |
| Base de datos no conecta | Verificar credenciales en `.env`, que MySQL está corriendo |
| Assets no cargan | Ejecutar `npm run build`, verificar `public/build/manifest.json` |
| Correo no funciona | Probar con `php artisan tinker`, verificar logs de correo |
| Almacenamiento no funciona | Ejecutar `php artisan storage:link`, verificar permisos |
| HTTPS no funciona | Verificar certificado de Let's Encrypt, permisos de carpeta |

---

## Documentación Adicional

- [Laravel Documentation](https://laravel.com/docs)
- [Let's Encrypt Documentación](https://letsencrypt.org/docs/)
- [Apache .htaccess Guide](https://httpd.apache.org/docs/current/howto/htaccess.html)
- [Nginx Configuration](https://nginx.org/en/docs/)

---

**Última actualización**: 6 de marzo de 2026

