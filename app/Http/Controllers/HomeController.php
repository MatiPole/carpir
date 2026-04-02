<?php

namespace App\Http\Controllers;

use App\Models\EscuchanosItem;
use App\Models\Fecha;
use App\Models\Integrante;
use App\Models\NosotrosConfig;
use App\Models\Noticia;

class HomeController extends Controller
{
    public function index()
    {
        $nosotros = NosotrosConfig::getConfig();
        $fechas = Fecha::where('fecha', '>=', now()->toDateString())->orderBy('fecha')->orderBy('id')->take(5)->get();
        $escuchanos = EscuchanosItem::orderBy('orden')->orderBy('id')->get()->slice(-2)->values();
        $noticias = Noticia::orderByFechaNewestFirst()->orderBy('id', 'desc')->take(3)->get();
        $integrantes = Integrante::where('activo', true)->orderBy('orden')->orderBy('id')->get();

        $lcpImagePreload = null;
        $portada = $nosotros->imagen_portada ?? null;
        if ($portada) {
            $lcpImagePreload = str_starts_with($portada, 'http')
                ? $portada
                : (str_starts_with($portada, '/') ? url($portada) : asset($portada));
        }

        return view('home', compact('nosotros', 'fechas', 'escuchanos', 'noticias', 'integrantes', 'lcpImagePreload'));
    }

    public function escuchanos()
    {
        $escuchanos = EscuchanosItem::orderBy('orden')->orderBy('id')->get();
        return view('escuchanos.index', compact('escuchanos'));
    }
}
