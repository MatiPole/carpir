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
        $escuchanos = EscuchanosItem::orderBy('orden')->orderBy('id')->get();
        $noticias = Noticia::orderBy('fecha', 'desc')->take(3)->get();
        $integrantes = Integrante::where('activo', true)->orderBy('orden')->orderBy('id')->get();

        return view('home', compact('nosotros', 'fechas', 'escuchanos', 'noticias', 'integrantes'));
    }
}
