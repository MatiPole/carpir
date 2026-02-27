<?php

namespace Database\Seeders;

use App\Models\Noticia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class NoticiaSeeder extends Seeder
{
    public function run(): void
    {
        if (Noticia::count() > 0) {
            return;
        }

        $paths = [
            public_path('assets/noticias.json'),
            base_path('public/assets/noticias.json'),
            base_path('database/seeders/data/noticias.json'),
        ];

        $jsonPath = null;
        foreach ($paths as $path) {
            if (File::exists($path)) {
                $jsonPath = $path;
                break;
            }
        }

        if (!$jsonPath) {
            return;
        }

        $json = File::get($jsonPath);
        $noticias = json_decode($json, true);

        if (!is_array($noticias)) {
            return;
        }

        foreach ($noticias as $n) {
            Noticia::create([
                'titulo' => $n['titulo'] ?? '',
                'fecha' => $n['fecha'] ?? '',
                'noticia' => $n['noticia'] ?? '',
                'img' => $n['img'] ?? [],
                'alt' => $n['alt'] ?? [],
                'imgExtras' => $n['imgExtras'] ?? [],
                'altExtras' => $n['altExtras'] ?? [],
                'videoClip' => !empty($n['videoClip']),
                'linkVideoClip' => $n['linkVideoClip'] ?? '',
            ]);
        }
    }
}
