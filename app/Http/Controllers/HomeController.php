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
        $noticias = Noticia::orderBy('created_at', 'desc')->orderBy('id', 'desc')->take(3)->get();
        $integrantes = Integrante::where('activo', true)->orderBy('orden')->orderBy('id')->get();

        return view('home', compact('nosotros', 'fechas', 'escuchanos', 'noticias', 'integrantes'));
    }

    public function escuchanos()
    {
        $escuchanos = EscuchanosItem::orderBy('orden')->orderBy('id')->get();
        return view('escuchanos.index', compact('escuchanos'));
    }
}
