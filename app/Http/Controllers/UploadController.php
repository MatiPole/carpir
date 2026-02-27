<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $allowedImage = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $allowedVideo = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];

        if ($type === 'img' && !in_array($file->getMimeType(), $allowedImage)) {
            return response()->json(['error' => 'Solo imágenes.'], 400);
        }
        if ($type === 'video' && !in_array($file->getMimeType(), $allowedVideo)) {
            return response()->json(['error' => 'Solo videos.'], 400);
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            return response()->json(['error' => 'Máximo 10MB.'], 400);
        }

        $path = $file->store($type === 'video' ? 'video' : 'img', 'public');
        $url = '/storage/' . str_replace('\\', '/', $path);

        return response()->json(['url' => $url, 'filename' => basename($path)]);
    }
}
