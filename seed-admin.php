<?php
/**
 * Crear usuario admin. Copiar a carpir-laravel/ y ejecutar: php seed-admin.php
 */
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = \App\Models\User::firstOrNew(['username' => 'admin']);
$u->password = bcrypt('admin123');
$u->save();
echo "Usuario admin listo (admin / admin123)\n";
