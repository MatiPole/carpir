@extends('layouts.app')

@section('title', 'Escuchanos | Carpir')
@section('meta_description', 'Escuchá la música de Carpir en Spotify y seguí las novedades de la banda.')
@section('canonical', route('escuchanos.index'))

@section('content')
<div class="escuchanos-page">
    <section class="escuchanos-section section">
        <h1 class="section-title">Escuchanos</h1>
        @if($escuchanos->isEmpty())
        <p class="escuchanos-empty">No hay contenido por el momento.</p>
        <a href="{{ route('home') }}#escuchanos" class="escuchanos-back">Volver al inicio</a>
        @else
        <div class="reproductor-container">
            @foreach($escuchanos as $it)
            <div class="reproductor">
                @if($it->titulo)<h3>{{ $it->titulo }}</h3>@endif
                <iframe src="{{ $it->embed_url }}" width="100%" height="{{ $it->titulo ? 380 : 352 }}" style="border-radius:12px" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy" title="{{ $it->titulo ?? 'Spotify' }}"></iframe>
            </div>
            @endforeach
        </div>
        <a href="{{ route('home') }}#escuchanos" class="escuchanos-back">Volver al inicio</a>
        @endif
    </section>
</div>
@endsection
