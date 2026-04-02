<?php

namespace App\Services;

class AdminUploadImageOptimizer
{
    /**
     * Escala la imagen (manteniendo proporción), recodifica a WebP o JPEG y guarda en disco.
     *
     * Usa funciones de carga específicas por tipo (imagecreatefromjpeg, etc.) en lugar de
     * file_get_contents + imagecreatefromstring para reducir el uso de memoria a la mitad
     * en imágenes grandes.
     *
     * @return array{filename: string}|null nombre del archivo dentro de $publicDiskDirectory
     */
    public static function optimizeToDirectory(
        string $sourceAbsolutePath,
        string $publicDiskDirectoryAbsolute,
        string $uuidBaseName,
        int $maxEdge,
        int $webpQuality,
        int $jpegQuality
    ): ?array {
        if (! is_readable($sourceAbsolutePath)) {
            return null;
        }

        // Elevar temporalmente el límite de memoria para imágenes de cámara (>4k px).
        $prevMemory = ini_get('memory_limit');
        self::ensureMemoryLimit('512M');

        try {
            $im = self::createFromFile($sourceAbsolutePath);
        } finally {
            ini_set('memory_limit', $prevMemory);
        }

        if ($im === null) {
            return null;
        }

        // Restaurar límite para el resto del proceso de optimización también necesita memoria
        self::ensureMemoryLimit('512M');

        try {
            $w = imagesx($im);
            $h = imagesy($im);
            if ($w < 1 || $h < 1) {
                imagedestroy($im);
                return null;
            }

            $work = self::scaleDownIfNeeded($im, $maxEdge);

            $dir = rtrim($publicDiskDirectoryAbsolute, DIRECTORY_SEPARATOR);

            if (function_exists('imagewebp')) {
                $filename = $uuidBaseName . '.webp';
                $path     = $dir . DIRECTORY_SEPARATOR . $filename;
                $q        = self::clampQuality($webpQuality);
                if (@imagewebp($work, $path, $q)) {
                    imagedestroy($work);
                    ini_set('memory_limit', $prevMemory);
                    return ['filename' => $filename];
                }
            }

            $filename = $uuidBaseName . '.jpg';
            $path     = $dir . DIRECTORY_SEPARATOR . $filename;
            $flat     = self::flattenOnWhite($work);
            imagedestroy($work);
            $q = self::clampQuality($jpegQuality);
            if (@imagejpeg($flat, $path, $q)) {
                imagedestroy($flat);
                ini_set('memory_limit', $prevMemory);
                return ['filename' => $filename];
            }

            imagedestroy($flat);
            ini_set('memory_limit', $prevMemory);
            return null;
        } catch (\Throwable) {
            ini_set('memory_limit', $prevMemory);
            return null;
        }
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Carga la imagen usando la función GD adecuada según el tipo MIME/extensión,
     * evitando la doble carga en memoria que implica imagecreatefromstring.
     */
    private static function createFromFile(string $path): ?\GdImage
    {
        $info = @getimagesize($path);
        if ($info === false) {
            return null;
        }

        $mime = $info['mime'] ?? '';

        $im = match ($mime) {
            'image/jpeg'            => @imagecreatefromjpeg($path),
            'image/png'             => @imagecreatefrompng($path),
            'image/webp'            => @imagecreatefromwebp($path),
            'image/gif'             => @imagecreatefromgif($path),
            'image/bmp'             => function_exists('imagecreatefrombmp') ? @imagecreatefrombmp($path) : false,
            'image/avif'            => function_exists('imagecreatefromavif') ? @imagecreatefromavif($path) : false,
            'image/tiff', 'image/tif' => false, // GD no soporta TIFF nativamente
            default                 => false,
        };

        if ($im === false || $im === null) {
            // Fallback: intenta con imagecreatefromstring (consume más memoria pero cubre más formatos)
            $binary = @file_get_contents($path);
            if ($binary === false || $binary === '') {
                return null;
            }
            $im = @imagecreatefromstring($binary);
            unset($binary);
        }

        return ($im instanceof \GdImage) ? $im : null;
    }

    /**
     * Sube el límite de memoria solo si el límite actual es inferior al solicitado.
     */
    private static function ensureMemoryLimit(string $needed): void
    {
        $current = self::parseBytes((string) ini_get('memory_limit'));
        $want    = self::parseBytes($needed);

        if ($current !== -1 && ($current < $want || $want === -1)) {
            ini_set('memory_limit', $needed);
        }
    }

    private static function parseBytes(string $val): int
    {
        $val  = trim($val);
        $last = strtolower($val[-1] ?? '');
        $num  = (int) $val;

        return match ($last) {
            'g' => $num * 1024 * 1024 * 1024,
            'm' => $num * 1024 * 1024,
            'k' => $num * 1024,
            '-' => -1,  // unlimited
            default => $num,
        };
    }

    private static function clampQuality(int $q): int
    {
        return max(0, min(100, $q));
    }

    private static function scaleDownIfNeeded(\GdImage $im, int $maxEdge): \GdImage
    {
        $w = imagesx($im);
        $h = imagesy($im);
        if ($w <= $maxEdge && $h <= $maxEdge) {
            return $im;
        }

        $ratio = min($maxEdge / $w, $maxEdge / $h);
        $newW  = max(1, (int) round($w * $ratio));
        $newH  = max(1, (int) round($h * $ratio));

        $dst = imagecreatetruecolor($newW, $newH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imageallocatealpha($dst, 0, 0, 0, 127);
        imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
        imagealphablending($dst, true);
        imagecopyresampled($dst, $im, 0, 0, 0, 0, $newW, $newH, $w, $h);
        imagedestroy($im);

        return $dst;
    }

    private static function flattenOnWhite(\GdImage $src): \GdImage
    {
        $w    = imagesx($src);
        $h    = imagesy($src);
        $flat = imagecreatetruecolor($w, $h);
        $white = imagecolorallocate($flat, 255, 255, 255);
        imagefilledrectangle($flat, 0, 0, $w, $h, $white);
        imagealphablending($flat, true);
        imagecopy($flat, $src, 0, 0, 0, 0, $w, $h);

        return $flat;
    }
}
