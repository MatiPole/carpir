# Despliegue de carpir-laravel en DreamHost (shared hosting)

Guía para subir el proyecto **carpir-laravel** a un hosting compartido DreamHost.

---

## Requisitos en DreamHost

- Cuenta con **PHP** (7.4+ u 8.x; recomendado 8.1+).
- **MySQL**: crear base de datos y usuario desde el panel (Goodies → MySQL).
- **SSH** activado (recomendado para `composer` y `artisan`). Si no tienes, ver opción sin SSH más abajo.
- Dominio o subdominio apuntando a la cuenta.

---

## 1. Preparar el proyecto en tu PC

### 1.1 Dependencias y clave

En la carpeta `carpir-laravel`:

```bash
cd carpir-laravel
composer install --no-dev --optimize-autoloader
php artisan key:generate
```

Copia el valor de `APP_KEY` que se guarda en `.env` (lo necesitarás en el servidor).

### 1.2 (Opcional) Build frontend

Si usas Vite/Node en este proyecto:

```bash
npm ci
npm run build
```

### 1.3 No subir

- Carpeta `node_modules/`
- Archivo `.env` (en el servidor crearás uno nuevo)
- Carpeta `.git/` si no quieres subir el repo

---

## 2. Estructura en DreamHost

En DreamHost el sitio suele vivir en una carpeta tipo:

- `~/tu-dominio.com/`  
  o  
- `~/carpir.com.ar/`

Lo importante: el **document root** del dominio debe ser la carpeta **`public`** de Laravel, no la raíz del proyecto.

### Opción A: Proyecto en subcarpeta (recomendada)

Estructura en el servidor:

```
~/tu-dominio.com/
└── carpir-laravel/
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── public/          ← El dominio debe apuntar AQUÍ como document root
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/
    ├── .env
    ├── artisan
    └── composer.json
```

En el panel de DreamHost:

1. **Domains** → tu dominio → **Edit**.
2. En **Web Directory** (o “Document root”) pon la ruta que corresponda, por ejemplo:
   - `carpir-laravel/public`  
   o la ruta absoluta que te muestre el panel (ej. `/home/tu_usuario/tu-dominio.com/carpir-laravel/public`).

Así solo se expone `public/`; el resto de Laravel queda fuera del document root.

### Opción B: Todo en la raíz del dominio

Si prefieres que la raíz del dominio sea la carpeta del proyecto:

1. Sube todo el contenido de `carpir-laravel` directamente dentro de `~/tu-dominio.com/` (de modo que `public` quede como `~/tu-dominio.com/public/`).
2. En el panel, define el **document root** del dominio como `public` (ej. `public` o la ruta absoluta a esa carpeta).

No uses como document root la carpeta que contiene `app/`, `config/`, etc.; siempre debe ser `public`.

---

## 3. Despliegue 100% por SSH

Si quieres hacer **todo por SSH** (subir archivos, instalar dependencias, migrar, etc.):

**Datos de conexión:**

| Dato     | Valor |
|----------|--------|
| Host     | `iad1-shared-d12-03.dreamhost.com` |
| Usuario  | `dh_6kvtfi` (reemplaza por tu usuario de DreamHost) |
| Ruta típica en el servidor | `~/tu-dominio.com/` (ej. `~/carpir.com.ar/`) |

### 3.1 Conectarte por SSH

Desde tu PC (PowerShell o Git Bash):

```bash
ssh dh_6kvtfi@iad1-shared-d12-03.dreamhost.com
```

Sustituye `dh_6kvtfi` por tu usuario. La primera vez te pedirá confirmar la huella del servidor.

### 3.2 Crear la carpeta del proyecto en el servidor

En el servidor (ya conectado por SSH), crea la carpeta donde vivirá Laravel. DreamHost suele usar el nombre del dominio:

```bash
mkdir -p ~/tu-dominio.com
# o, por ejemplo:
# mkdir -p ~/carpir.com.ar
```

Si la carpeta ya existe, no hace falta crearla.

### 3.3 Subir los archivos desde tu PC

**Opción A – Con `rsync` (recomendado, excluye node_modules y .env):**

Desde tu PC, en la carpeta donde está `carpir-laravel` (el padre, no dentro de carpir-laravel):

```bash
rsync -avz --exclude 'node_modules' --exclude '.env' --exclude '.git' \
  carpir-laravel/ dh_6kvtfi@iad1-shared-d12-03.dreamhost.com:~/tu-dominio.com/carpir-laravel/
```

Ajusta `tu-dominio.com` a la ruta real (ej. `carpir.com.ar`). Así **no** subes `vendor/`; lo instalarás en el servidor en el siguiente paso.

**Opción B – Subir un zip y descomprimir en el servidor:**

En tu PC (dentro de la carpeta que contiene `carpir-laravel`):

```bash
# Crear zip sin node_modules ni .git
tar -czvf carpir-laravel.tar.gz --exclude='carpir-laravel/node_modules' --exclude='carpir-laravel/.git' --exclude='carpir-laravel/.env' carpir-laravel
# Subir
scp carpir-laravel.tar.gz dh_6kvtfi@iad1-shared-d12-03.dreamhost.com:~/tu-dominio.com/
```

Luego en el servidor (por SSH):

```bash
cd ~/tu-dominio.com
tar -xzvf carpir-laravel.tar.gz
# Si el zip creó carpir-laravel/carpir-laravel/,
# mueve el contenido al nivel correcto para que ~/tu-dominio.com/carpir-laravel/ tenga app/, public/, etc.
```

### 3.4 En el servidor: instalar dependencias y configurar

Conéctate por SSH (si no lo estás ya) y ve a la raíz del proyecto:

```bash
ssh TU_USUARIO@iad1-shared-d12-03.dreamhost.com
cd ~/tu-dominio.com/carpir-laravel
```

Instalar dependencias de PHP (sin dev, para producción):

```bash
composer install --no-dev --optimize-autoloader
```

Crear el archivo `.env` (cópialo desde el ejemplo y edita con `nano` o `vim`):

```bash
cp .env.example .env
nano .env
```

Pon tu `APP_KEY` (el de tu PC), `APP_URL`, y los datos de MySQL de DreamHost (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). Guarda y cierra (en nano: Ctrl+O, Enter, Ctrl+X).

Generar clave si no usaste la de tu PC:

```bash
php artisan key:generate
```

Permisos:

```bash
chmod -R 775 storage bootstrap/cache
```

Migraciones y seeders:

```bash
php artisan migrate --force
php artisan db:seed --force
```

Enlace de storage (si la app usa archivos en `storage/app/public`):

```bash
php artisan storage:link
```

Opcional: limpiar caché de config:

```bash
php artisan config:cache
```

### 3.5 Document root en el panel DreamHost

En **Domains** → tu dominio → **Edit**, configura el **Web Directory** para que apunte a la carpeta **public** del proyecto, por ejemplo:

- `carpir-laravel/public`  
o la ruta absoluta que muestre el panel, p. ej. `/home/TU_USUARIO/tu-dominio.com/carpir-laravel/public`.

---

## 4. Subir archivos (por SFTP si no usas SSH)

- Por **SFTP** (FileZilla, WinSCP, etc.): conectarte a `iad1-shared-d12-03.dreamhost.com` con tu usuario y subir todo el contenido de `carpir-laravel` al lugar elegido (subcarpeta o raíz), **incluida** la carpeta `vendor/` si ya hiciste `composer install` en tu PC.
- Si usas **SSH** como arriba: no hace falta subir `vendor/`; lo instalas en el servidor con `composer install --no-dev`.

---

## 5. Configurar el servidor (SSH o panel)

### 5.1 Crear `.env` en el servidor

En la **raíz del proyecto Laravel** (donde está `artisan`), crea o edita `.env` con algo como:

```env
APP_NAME="Carpir"
APP_ENV=production
APP_KEY=base64:XXXX...   # La misma que generaste en tu PC (o genera una con php artisan key:generate)
APP_DEBUG=false
APP_URL=https://tu-dominio.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# MySQL en DreamHost: NO uses "localhost", usa el hostname del panel
DB_CONNECTION=mysql
DB_HOST=mysql.tudominio.dreamhosters.com
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=usuario_mysql
DB_PASSWORD=contraseña_mysql

SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database
QUEUE_CONNECTION=database
```

- `DB_HOST`: en DreamHost suele ser algo como `mysql.tudominio.dreamhosters.com`. Lo ves en el panel de MySQL.
- `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: los que creaste para la base MySQL.

### 5.2 Permisos de escritura

Por SSH (desde la raíz del proyecto Laravel):

```bash
chmod -R 775 storage bootstrap/cache
```

Si el servidor usa otro usuario (ej. el del webserver), puede que necesites `chmod -R 777 storage bootstrap/cache` (solo si 775 no funciona).

### 5.3 Migraciones y datos iniciales

Por SSH, en la raíz del proyecto:

```bash
php artisan migrate --force
php artisan db:seed --force
```

Si tienes un seeder de admin (por ejemplo `seed-admin.php` o comando propio), ejecútalo también.

### 5.4 Enlace simbólico de `storage` (si usas archivos públicos)

Para que `public/storage` sirva los archivos de `storage/app/public`:

```bash
php artisan storage:link
```

---

## 6. Si NO tienes SSH (solo FTP/SFTP)

1. **Subir todo**, incluida la carpeta **`vendor/`** después de ejecutar en tu PC:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
2. Crear **`.env`** en el servidor (mismo contenido que arriba) subiendo un archivo o editando por el administrador de archivos del panel.
3. **Permisos**: desde el panel de archivos de DreamHost, dar a `storage` y `bootstrap/cache` permisos 775 (o 777 si es necesario).
4. **Migraciones**: DreamHost a veces ofrece “Run PHP script” o “Cron”. Puedes crear un script temporal que ejecute las migraciones (por ejemplo incluyendo `require 'vendor/autoload.php';` y arrancando la app para ejecutar `Artisan::call('migrate', ['--force' => true]);`). O pedir a soporte que ejecute una vez:
   ```bash
   php /home/tu_usuario/tu-dominio.com/carpir-laravel/artisan migrate --force
   ```

---

## 7. Comprobar que funciona

- Abrir `https://tu-dominio.com`: debe cargar la página principal.
- Probar login y panel de admin.
- Probar subida de imágenes/videos si aplica (revisar permisos de `storage/app/public` o el disco que uses).

---

## 7.1 Si ves la versión vieja del sitio o sin estilos (sobre todo en móvil)

**Síntoma:** En el navegador sigue apareciendo la web antigua (p. ej. el antiguo `index.html`) o la nueva web se ve sin CSS, sobre todo en móvil.

**Causa habitual:** El **document root** del dominio sigue apuntando a la carpeta antigua (donde estaban `index.html`, `assets/`, etc.), no a la carpeta **`public`** de Laravel.

**Qué hacer:**

1. **Cambiar el document root en DreamHost**
   - Entra en **Domains** → tu dominio (ej. carpir.com.ar) → **Edit**.
   - En **Web Directory** (o “Document root”) pon la ruta a la carpeta **`public`** del proyecto Laravel, por ejemplo:
     - `carpir-laravel/public`  
     si el proyecto está en `~/carpir.com.ar/carpir-laravel/`,  
     o la ruta absoluta que indique el panel, p. ej. `/home/dh_6kvtfi/carpir.com.ar/carpir-laravel/public`.
   - Guarda los cambios. Los cambios de document root pueden tardar unos minutos.

2. **Revisar `APP_URL` en el servidor**
   - En la raíz del proyecto, edita `.env` y asegúrate de tener:
     ```env
     APP_URL=https://carpir.com.ar
     ```
     (o la URL exacta de tu dominio, con `https://`).
   - Si la web se abre por `https://` y `APP_URL` es `http://`, los enlaces a CSS/JS pueden fallar (sobre todo en móvil). Debe coincidir el protocolo.

3. **Refrescar la configuración de Laravel (por SSH)**
   ```bash
   cd ~/carpir.com.ar/carpir-laravel
   php artisan config:clear
   php artisan config:cache
   ```

4. **Comprobar que los CSS existen dentro de `public`**
   - Por SSH: `ls -la ~/carpir.com.ar/carpir-laravel/public/assets/`
   - Debe haber al menos `app.css` o `style.css` (y la carpeta `img/` con imágenes). Si no están, hay que subir de nuevo la carpeta `public/assets/` del proyecto.

5. **Caché del navegador**
   - Prueba en modo incógnito o borra caché del sitio. En móvil, cierra pestañas y vuelve a abrir la URL.

---

## 8. Seguridad

- Dejar **APP_DEBUG=false** y **APP_ENV=production** en producción. Con **APP_DEBUG=true** en producción se pueden filtrar datos sensibles en los errores.
- En producción usar **LOG_LEVEL=error** (o `warning`), no `debug`.
- Cambiar la contraseña del usuario admin por defecto.
- No subir `.env` con datos reales a repositorios públicos.

---

## 9. Resumen rápido

| Paso | Acción |
|------|--------|
| 1 | En PC: `composer install --no-dev`, `php artisan key:generate` |
| 2 | Subir todo el proyecto (incl. `vendor` si no tienes SSH) a la carpeta elegida |
| 3 | Document root del dominio = carpeta **`public`** del proyecto |
| 4 | Crear `.env` en la raíz del proyecto con MySQL de DreamHost (DB_HOST = hostname del panel) |
| 5 | Permisos 775 en `storage` y `bootstrap/cache` |
| 6 | Ejecutar `php artisan migrate --force` y `php artisan db:seed --force` (y `storage:link` si aplica) |

Si indicas si tienes o no SSH en DreamHost, se puede afinar solo esa parte (por ejemplo, script para migrar sin SSH).
