<?php

namespace App\Console\Commands;

use App\Services\AdminUploadImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OptimizeStorageImages extends Command
{
    protected $signature = 'images:optimize
                            {--max-edge=1200 : Lado máximo en píxeles}
                            {--webp-quality=82 : Calidad WebP (0-100)}
                            {--jpeg-quality=82 : Calidad JPEG (0-100)}
                            {--dry-run : Mostrar qué se haría sin modificar nada}
                            {--dir=img : Directorio dentro de storage/public}';

    protected $description = 'Re-optimiza las imágenes existentes en storage/public reduciendo su tamaño máximo.';

    public function handle(): int
    {
        // Imágenes de cámara de >5000px necesitan >150 MB solo para decodificarse en GD.
        ini_set('memory_limit', '512M');

        $maxEdge    = (int) $this->option('max-edge');
        $webpQ      = (int) $this->option('webp-quality');
        $jpegQ      = (int) $this->option('jpeg-quality');
        $dryRun     = (bool) $this->option('dry-run');
        $relDir     = ltrim((string) $this->option('dir'), '/\\');
        $absDir     = Storage::disk('public')->path($relDir);

        if (! is_dir($absDir)) {
            $this->error("El directorio no existe: {$absDir}");
            return self::FAILURE;
        }

        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'bmp', 'tiff', 'tif', 'avif'];
        $files = array_filter(
            scandir($absDir) ?: [],
            fn ($f) => in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), $extensions, true)
        );

        $this->info(sprintf(
            'Directorio: %s | Archivos imagen: %d | max-edge: %dpx | dry-run: %s',
            $relDir,
            count($files),
            $maxEdge,
            $dryRun ? 'sí' : 'no'
        ));

        $processed = $skipped = $failed = 0;

        foreach ($files as $filename) {
            $absPath = $absDir . DIRECTORY_SEPARATOR . $filename;

            [$w, $h] = @getimagesize($absPath) ?: [0, 0];
            if ($w <= 0 || $h <= 0) {
                $this->line("  <fg=yellow>SKIP</> {$filename}  (no se pudo leer tamaño)");
                $skipped++;
                continue;
            }

            if ($w <= $maxEdge && $h <= $maxEdge) {
                $this->line("  <fg=gray>OK</> {$filename}  ({$w}×{$h}, ya dentro del límite)");
                $skipped++;
                continue;
            }

            $kb = round(filesize($absPath) / 1024);
            $this->line("  <fg=red>GRANDE</> {$filename}  ({$w}×{$h}, {$kb} KiB)");

            if ($dryRun) {
                $processed++;
                continue;
            }

            $baseName = Str::uuid()->toString();
            $result   = AdminUploadImageOptimizer::optimizeToDirectory(
                $absPath,
                $absDir,
                $baseName,
                $maxEdge,
                $webpQ,
                $jpegQ,
            );

            if ($result === null) {
                $this->error("    → Falló la optimización de {$filename}");
                $failed++;
                continue;
            }

            $newFilename = $result['filename'];
            $newPath     = $absDir . DIRECTORY_SEPARATOR . $newFilename;
            $newKb       = round(filesize($newPath) / 1024);

            // Actualiza la base de datos reemplazando la URL vieja por la nueva
            $relOld = '/storage/' . $relDir . '/' . $filename;
            $relNew = '/storage/' . $relDir . '/' . $newFilename;
            $this->updateDatabaseReferences($relOld, $relNew);

            // Elimina el archivo original
            @unlink($absPath);

            $saving = $kb - $newKb;
            $this->line(
                "    → {$newFilename} ({$newKb} KiB, ‑{$saving} KiB)" .
                ($relOld !== $relNew ? "  [DB actualizada]" : '')
            );

            $processed++;
        }

        $this->info("Listo: {$processed} procesadas, {$skipped} omitidas, {$failed} fallidas.");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Reemplaza la URL vieja por la nueva en todas las columnas de texto de las tablas relevantes.
     */
    private function updateDatabaseReferences(string $oldUrl, string $newUrl): void
    {
        $tables = [
            ['table' => 'nosotros_config', 'columns' => ['imagen_portada']],
            ['table' => 'integrantes',     'columns' => ['imagen']],
            ['table' => 'noticias',        'columns' => ['img', 'img_extras']],
        ];

        foreach ($tables as $entry) {
            foreach ($entry['columns'] as $col) {
                try {
                    \DB::table($entry['table'])
                        ->whereRaw("LOCATE(?, `{$col}`) > 0", [$oldUrl])
                        ->update([
                            $col => \DB::raw("REPLACE(`{$col}`, " . \DB::getPdo()->quote($oldUrl) . ', ' . \DB::getPdo()->quote($newUrl) . ')'),
                        ]);
                } catch (\Throwable) {
                    // Si la tabla/columna no existe en esta instalación, se ignora
                }
            }
        }
    }
}
