@extends('layouts.admin-form')

@section('title', ($item ? 'Editar item Spotify' : 'Nuevo item Spotify') . ' | Carpir Admin')
@section('modal_title', $item ? 'Editar item de Spotify' : 'Nuevo item de Spotify')

@section('content')
<p style="font-size:0.95rem;color:#666;margin:0 0 1rem;">Pegá el enlace que te da Spotify al hacer «Copiar enlace» (álbum, tema, artista o playlist).</p>

<form method="POST" action="{{ $item ? route('admin.escuchanos.update', $item->id) : route('admin.escuchanos.store') }}">
    @csrf
    @if($item) @method('PUT') @endif

    <div class="form-group">
        <label for="titulo">Título (opcional)</label>
        <input id="titulo" type="text" name="titulo" value="{{ old('titulo', $item->titulo ?? '') }}" placeholder="ej. Despertar (EP)">
    </div>

    <div class="form-group">
        <label for="embed_url">URL de embed de Spotify *</label>
        <input id="embed_url" type="url" name="embed_url" value="{{ old('embed_url', $item->embed_url ?? '') }}" placeholder="https://open.spotify.com/embed/album/..." required>
    </div>

    <div class="form-group">
        <label for="orden">Orden</label>
        <input id="orden" type="number" name="orden" value="{{ old('orden', $item->orden ?? 0) }}">
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.index') }}" class="cancel-button">Cancelar</a>
        <button type="submit" class="save-button">Guardar</button>
    </div>
</form>
@endsection
