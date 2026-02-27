# Configuración en Windows (PHP, Composer, Laravel)

Problemas frecuentes al ejecutar `composer install` o `php artisan` en Windows.

---

## 1. Error: "ext-fileinfo" missing / "Install or enable PHP's fileinfo extension"

Laravel y paquetes como `league/flysystem` necesitan la extensión **fileinfo** para detectar tipos MIME.

En tu `php.ini` (en Windows: `php --ini` para ver la ruta):

- Busca: `;extension=fileinfo`
- Cámbialo a: `extension=fileinfo` (quita el `;`)

Comprueba con: `php -m | findstr fileinfo`

---

## 2. Error: "The openssl extension is required for SSL/TLS protection"

Composer necesita la extensión **OpenSSL** de PHP para descargar paquetes por HTTPS.

### Cómo habilitar OpenSSL en Windows

1. **Localizar tu `php.ini`**
   - En la terminal: `php --ini`
   - Verás algo como: `Loaded Configuration File: C:\php\php.ini` (o dentro de XAMPP, Laragon, etc.)

2. **Editar `php.ini`**
   - Abre ese archivo como administrador.
   - Busca la línea: `;extension=openssl`
   - Quita el punto y coma del inicio: `extension=openssl`
   - Guarda el archivo.

3. **Comprobar**
   ```bash
   php -m | findstr openssl
   ```
   Debe mostrar `openssl`.

4. **Volver a instalar dependencias**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
   O para desarrollo (incluye Pail, Sail, etc.):
   ```bash
   composer install
   ```

---

## 3. Error: "Class Laravel\Pail\PailServiceProvider not found"

Suele aparecer cuando:
- `composer install` no llegó a completarse (por ejemplo por el error de OpenSSL), o
- Se usó `composer install --no-dev` y la caché de Laravel sigue esperando el paquete de desarrollo Pail.

### Solución

**Opción A – Instalar dependencias de desarrollo (recomendado en local):**
```bash
composer install
php artisan key:generate
```

**Opción B – Seguir sin paquetes dev y limpiar caché:**
```bash
composer install --no-dev --optimize-autoloader
del bootstrap\cache\packages.php
del bootstrap\cache\services.php
php artisan package:discover
php artisan key:generate
```

---

## 4. Advertencias de deprecación de Symfony en Composer

Las advertencias tipo `Implicitly marking parameter ... as nullable is deprecated` vienen del **Composer** (phar), no de tu proyecto. No bloquean la instalación. Para evitarlas puedes actualizar Composer:

```bash
composer self-update
```

O ignorarlas; no afectan a Laravel.

---

## Orden recomendado en Windows

```bash
cd carpir-laravel
# 1. Habilitar openssl en php.ini si hace falta (ver arriba)
composer install
php artisan key:generate
# Si usas frontend con Vite:
# npm ci && npm run build
```
