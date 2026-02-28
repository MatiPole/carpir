<?php

namespace App\Http\Controllers;

use App\Models\EscuchanosItem;
use App\Models\Fecha;
use App\Models\Integrante;
use App\Models\NosotrosConfig;
use App\Models\Noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $noticias = Noticia::orderBy('created_at', 'desc')->orderBy('id', 'desc')->get();
        $integrantes = Integrante::orderBy('orden')->orderBy('id')->get();
        $nosotros = NosotrosConfig::getConfig();
        $escuchanos = EscuchanosItem::orderBy('orden')->orderBy('id')->get();
        $fechas = Fecha::orderBy('fecha')->orderBy('id')->get();

        return view('admin.index', compact('noticias', 'integrantes', 'nosotros', 'escuchanos', 'fechas'));
    }

    public function createNoticia()
    {
        return view('admin.forms.noticia', ['noticia' => null]);
    }

    public function editNoticia($id)
    {
        $noticia = Noticia::findOrFail($id);
        return view('admin.forms.noticia', ['noticia' => $noticia]);
    }

    public function storeNoticia(Request $request)
    {
        $data = $request->validate([
            'titulo' => 'required|string',
            'fecha' => 'required|string',
            'noticia' => 'required|string',
            'img' => 'nullable|array',
            'alt' => 'nullable|array',
            'imgExtras' => 'nullable|array',
            'altExtras' => 'nullable|array',
            'videoClip' => 'nullable',
            'linkVideoClip' => 'nullable|string',
        ]);
        $img = $data['img'] ?? [];
        $alt = $data['alt'] ?? [];
        $filteredImg = [];
        $filteredAlt = [];
        foreach (array_keys($img) as $i) {
            $v = $img[$i] ?? '';
            if ($v !== '' && $v !== null) {
                $filteredImg[] = $v;
                $filteredAlt[] = $alt[$i] ?? '';
            }
        }
        $data['img'] = $filteredImg;
        $data['alt'] = $filteredAlt;
        $imgExtras = $data['imgExtras'] ?? [];
        $altExtras = $data['altExtras'] ?? [];
        $filteredExtras = [];
        $filteredAltExtras = [];
        foreach (array_keys($imgExtras) as $i) {
            $v = $imgExtras[$i] ?? '';
            if ($v !== '' && $v !== null) {
                $filteredExtras[] = $v;
                $filteredAltExtras[] = $altExtras[$i] ?? '';
            }
        }
        $data['imgExtras'] = $filteredExtras;
        $data['altExtras'] = $filteredAltExtras;
        $data['videoClip'] = !empty($request->videoClip);
        $data['linkVideoClip'] = $data['linkVideoClip'] ?? '';
        Noticia::create($data);
        return redirect()->to(route('admin.index').'#noticias')->with('success', 'Noticia creada.');
    }

    public function updateNoticia(Request $request, $id)
    {
        $noticia = Noticia::findOrFail($id);
        $data = $request->validate([
            'titulo' => 'required|string',
            'fecha' => 'required|string',
            'noticia' => 'required|string',
            'img' => 'nullable|array',
            'alt' => 'nullable|array',
            'imgExtras' => 'nullable|array',
            'altExtras' => 'nullable|array',
            'videoClip' => 'nullable',
            'linkVideoClip' => 'nullable|string',
        ]);
        $img = $data['img'] ?? $noticia->img ?? [];
        $alt = $data['alt'] ?? $noticia->alt ?? [];
        $filteredImg = [];
        $filteredAlt = [];
        foreach (array_keys($img) as $i) {
            $v = $img[$i] ?? '';
            if ($v !== '' && $v !== null) {
                $filteredImg[] = $v;
                $filteredAlt[] = $alt[$i] ?? '';
            }
        }
        $data['img'] = $filteredImg;
        $data['alt'] = $filteredAlt;
        $imgExtras = $data['imgExtras'] ?? $noticia->imgExtras ?? [];
        $altExtras = $data['altExtras'] ?? $noticia->altExtras ?? [];
        $filteredExtras = [];
        $filteredAltExtras = [];
        foreach (array_keys($imgExtras) as $i) {
            $v = $imgExtras[$i] ?? '';
            if ($v !== '' && $v !== null) {
                $filteredExtras[] = $v;
                $filteredAltExtras[] = $altExtras[$i] ?? '';
            }
        }
        $data['imgExtras'] = $filteredExtras;
        $data['altExtras'] = $filteredAltExtras;
        $data['videoClip'] = !empty($request->videoClip);
        $data['linkVideoClip'] = $data['linkVideoClip'] ?? '';
        $noticia->update($data);
        return redirect()->to(route('admin.index').'#noticias')->with('success', 'Noticia actualizada.');
    }

    public function destroyNoticia($id)
    {
        Noticia::findOrFail($id)->delete();
        return redirect()->to(route('admin.index').'#noticias')->with('success', 'Noticia eliminada.');
    }

    public function createIntegrante()
    {
        return view('admin.forms.integrante', ['integrante' => null]);
    }

    public function editIntegrante($id)
    {
        $integrante = Integrante::findOrFail($id);
        return view('admin.forms.integrante', ['integrante' => $integrante]);
    }

    public function storeIntegrante(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'rol' => 'required|string',
        ]);
        Integrante::create([
            'nombre' => $request->nombre,
            'rol' => $request->rol,
            'imagen' => $request->imagen ?? '',
            'activo' => !empty($request->activo),
            'orden' => (int)($request->orden ?? 0),
        ]);
        return redirect()->to(route('admin.index').'#integrantes')->with('success', 'Integrante creado.');
    }

    public function updateIntegrante(Request $request, $id)
    {
        $i = Integrante::findOrFail($id);
        $request->validate([
            'nombre' => 'required|string',
            'rol' => 'required|string',
        ]);
        $i->update([
            'nombre' => $request->nombre,
            'rol' => $request->rol,
            'imagen' => $request->imagen ?? '',
            'activo' => !empty($request->activo),
            'orden' => (int)($request->orden ?? 0),
        ]);
        return redirect()->to(route('admin.index').'#integrantes')->with('success', 'Integrante actualizado.');
    }

    public function toggleIntegrante($id)
    {
        $i = Integrante::findOrFail($id);
        $i->update(['activo' => !$i->activo]);
        return redirect()->to(route('admin.index').'#integrantes')->with('success', $i->activo ? 'Integrante activado.' : 'Integrante inactivado.');
    }

    public function updateNosotros(Request $request)
    {
        $request->validate([
            'descripcion' => 'required|string',
            'descripcion_extra' => 'nullable|string',
            'imagen_portada' => 'nullable|string',
        ]);
        NosotrosConfig::updateOrCreate(
            ['id' => 1],
            [
                'descripcion' => $request->descripcion,
                'descripcion_extra' => $request->descripcion_extra ?? '',
                'imagen_portada' => $request->imagen_portada ?? '',
            ]
        );
        return redirect()->to(route('admin.index').'#nosotros')->with('success', 'Nosotros actualizado.');
    }

    public function createEscuchanos()
    {
        return view('admin.forms.escuchanos', ['item' => null]);
    }

    public function editEscuchanos($id)
    {
        $item = EscuchanosItem::findOrFail($id);
        return view('admin.forms.escuchanos', ['item' => $item]);
    }

    public function storeEscuchanos(Request $request)
    {
        $request->validate([
            'titulo' => 'nullable|string',
            'embed_url' => 'required|string',
            'orden' => 'nullable|integer',
        ]);
        EscuchanosItem::create([
            'titulo' => $request->titulo ?? '',
            'embed_url' => $request->embed_url,
            'orden' => (int)($request->orden ?? 0),
        ]);
        return redirect()->to(route('admin.index').'#escuchanos')->with('success', 'Item de Spotify creado.');
    }

    public function updateEscuchanos(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'nullable|string',
            'embed_url' => 'required|string',
            'orden' => 'nullable|integer',
        ]);
        EscuchanosItem::findOrFail($id)->update([
            'titulo' => $request->titulo ?? '',
            'embed_url' => $request->embed_url,
            'orden' => (int)($request->orden ?? 0),
        ]);
        return redirect()->to(route('admin.index').'#escuchanos')->with('success', 'Item actualizado.');
    }

    public function destroyEscuchanos($id)
    {
        EscuchanosItem::findOrFail($id)->delete();
        return redirect()->to(route('admin.index').'#escuchanos')->with('success', 'Item eliminado.');
    }

    public function createFecha()
    {
        return view('admin.forms.fecha', ['fecha' => null]);
    }

    public function editFecha($id)
    {
        $fecha = Fecha::findOrFail($id);
        return view('admin.forms.fecha', ['fecha' => $fecha]);
    }

    public function storeFecha(Request $request)
    {
        $request->validate(['fecha' => 'required|date']);
        Fecha::create([
            'fecha' => $request->fecha,
            'locacion' => $request->locacion ?? '',
            'direccion' => $request->direccion ?? '',
            'horario' => $request->horario ?? '',
            'costo' => $request->costo ?? '',
            'link_entradas' => $request->link_entradas ?? '',
        ]);
        return redirect()->to(route('admin.index').'#fechas')->with('success', 'Fecha creada.');
    }

    public function updateFecha(Request $request, $id)
    {
        $request->validate(['fecha' => 'required|date']);
        Fecha::findOrFail($id)->update([
            'fecha' => $request->fecha,
            'locacion' => $request->locacion ?? '',
            'direccion' => $request->direccion ?? '',
            'horario' => $request->horario ?? '',
            'costo' => $request->costo ?? '',
            'link_entradas' => $request->link_entradas ?? '',
        ]);
        return redirect()->to(route('admin.index').'#fechas')->with('success', 'Fecha actualizada.');
    }

    public function destroyFecha($id)
    {
        Fecha::findOrFail($id)->delete();
        return redirect()->to(route('admin.index').'#fechas')->with('success', 'Fecha eliminada.');
    }
}
