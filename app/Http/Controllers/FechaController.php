<?php

namespace App\Http\Controllers;

use App\Models\Fecha;

class FechaController extends Controller
{
    public function index()
    {
        $fechas = Fecha::where('fecha', '>=', now()->toDateString())->orderBy('fecha')->orderBy('id')->get();
        return view('fechas.index', compact('fechas'));
    }
}
