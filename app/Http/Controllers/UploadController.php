<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
            'type' => 'in:img,video',
        ]);

        $type = $request->input('type', 'img');
        $file = $request->file('file');

        $allowedImage = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/x-png',
            'image/gif',
            'image/webp',
            'image/bmp',
            'image/svg+xml',
            'image/tiff',
            'image/x-icon',
            'image/avif',
        ];
        $allowedVideo = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];

        $mime = $file->getMimeType();
        $size = $file->getSize();

        if ($type === 'img' && !in_array($mime, $allowedImage)) {
            $msg = 'Formato de imagen no permitido. El archivo se detectó como: ' . $mime . '. Usá JPG, PNG, GIF, WebP, BMP, SVG, TIFF, ICO o AVIF.';
            return response()->json(['error' => $msg], 400);
        }
        if ($type === 'video' && !in_array($mime, $allowedVideo)) {
            return response()->json(['error' => 'Solo videos MP4, WebM, OGG o MOV.'], 400);
        }

        $maxBytes = 20 * 1024 * 1024;
        if ($size > $maxBytes) {
            $mb = round($size / (1024 * 1024), 1);
            return response()->json(['error' => 'El archivo pesa ' . $mb . ' MB. Máximo 20 MB.'], 400);
        }

        try {
            $dir = $type === 'video' ? 'video' : 'img';
            if (!Storage::disk('public')->exists($dir)) {
                Storage::disk('public')->makeDirectory($dir);
            }
            $ext = $file->getClientOriginalExtension() ?: $file->guessExtension();
            $safeName = Str::uuid()->toString() . ($ext ? '.' . $ext : '');
            $path = $file->storeAs($dir, $safeName, 'public');
            $url = '/storage/' . str_replace('\\', '/', $path);
            return response()->json(['url' => $url, 'filename' => $safeName]);
        } catch (\Throwable $e) {
            $message = config('app.debug') ? $e->getMessage() : 'Error al guardar el archivo. Revisá permisos de storage/app/public.';
            return response()->json(['error' => $message], 500);
        }
    }
}
