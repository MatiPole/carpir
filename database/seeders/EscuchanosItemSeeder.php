<?php

namespace Database\Seeders;

use App\Models\EscuchanosItem;
use Illuminate\Database\Seeder;

class EscuchanosItemSeeder extends Seeder
{
    public function run(): void
    {
        if (EscuchanosItem::count() > 0) {
            return;
        }

        $items = [
            ['titulo' => 'Despertar (EP)', 'embed_url' => 'https://open.spotify.com/embed/album/4DUW4yu0f4WaBJ4F4UhSjB?utm_source=generator&theme=0', 'orden' => 1],
            ['titulo' => 'A Veces (Single)', 'embed_url' => 'https://open.spotify.com/embed/track/10HSDelFISJWuToizIqo3T?utm_source=generator&theme=0', 'orden' => 2],
        ];

        foreach ($items as $i) {
            EscuchanosItem::create([
                'titulo' => $i['titulo'],
                'embed_url' => $i['embed_url'],
                'orden' => $i['orden'],
            ]);
        }
    }
}
