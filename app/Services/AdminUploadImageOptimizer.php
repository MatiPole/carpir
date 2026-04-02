<?php

namespace App\Services;

class AdminUploadImageOptimizer
{
    /**
     * Escala la imagen (manteniendo proporción), recodifica a WebP o JPEG y guarda en disco.
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

        $binary = @file_get_contents($sourceAbsolutePath);
        if ($binary === false || $binary === '') {
            return null;
        }

        $im = @imagecreatefromstring($binary);
        if ($im === false) {
            return null;
        }

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
                $filename = $uuidBaseName.'.webp';
                $path = $dir.DIRECTORY_SEPARATOR.$filename;
                $q = self::clampQuality($webpQuality);
                if (@imagewebp($work, $path, $q)) {
                    imagedestroy($work);

                    return ['filename' => $filename];
                }
            }

            $filename = $uuidBaseName.'.jpg';
            $path = $dir.DIRECTORY_SEPARATOR.$filename;
            $flat = self::flattenOnWhite($work);
            imagedestroy($work);
            $work = $flat;
            $q = self::clampQuality($jpegQuality);
            if (@imagejpeg($work, $path, $q)) {
                imagedestroy($work);

                return ['filename' => $filename];
            }

            imagedestroy($work);

            return null;
        } catch (\Throwable) {
            return null;
        }
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
        $newW = max(1, (int) round($w * $ratio));
        $newH = max(1, (int) round($h * $ratio));

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
        $w = imagesx($src);
        $h = imagesy($src);
        $flat = imagecreatetruecolor($w, $h);
        $white = imagecolorallocate($flat, 255, 255, 255);
        imagefilledrectangle($flat, 0, 0, $w, $h, $white);
        imagealphablending($flat, true);
        imagecopy($flat, $src, 0, 0, 0, 0, $w, $h);

        return $flat;
    }
}
