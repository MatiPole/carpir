@extends('layouts.app')

@section('title', $noticia->titulo . ' | Carpir')
@section('meta_description', \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($noticia->noticia))), 155, '...'))
@section('canonical', route('noticias.show', $noticia->id))
@section('og_type', 'article')

@section('content')
<article class="noticia-detalle section">
    <h1>{{ $noticia->titulo }}<span class="fecha-badge">{{ $noticia->fecha }}</span></h1>
    <div class="noticia-body noticia-texto">
        @if(strip_tags($noticia->noticia) !== $noticia->noticia)
            {!! $noticia->noticia !!}
        @else
            {!! nl2br(e($noticia->noticia)) !!}
        @endif
    </div>
    @php
        $isVideoUrl = function($u) { return $u && preg_match('/\.(mp4|webm|ogg|mov)(\?|$)/i', $u); };
    @endphp
    @if(is_array($noticia->img ?? null) && count(array_filter($noticia->img)))
    <div class="noticia-detalle-gallery">
        @foreach($noticia->img as $idx => $url)
            @if($url)
            <figure>
                @if($isVideoUrl($url))
                <video src="{{ (str_starts_with($url, 'http') || str_starts_with($url, '/')) ? $url : asset($url) }}" controls playsinline width="800" height="450"></video>
                @else
                <img src="{{ (str_starts_with($url, 'http') || str_starts_with($url, '/')) ? $url : asset($url) }}" alt="{{ is_array($noticia->alt) ? ($noticia->alt[$idx] ?? '') : '' }}" width="800" height="800" decoding="async">
                @endif
            </figure>
            @endif
        @endforeach
    </div>
    @endif
    @if(is_array($noticia->imgExtras ?? null) && count(array_filter($noticia->imgExtras)))
    <div class="noticia-detalle-gallery noticia-detalle-extras">
        <h3>Más fotos y videos</h3>
        @foreach($noticia->imgExtras as $idx => $url)
            @if($url)
            <figure>
                @if($isVideoUrl($url))
                <video src="{{ (str_starts_with($url, 'http') || str_starts_with($url, '/')) ? $url : asset($url) }}" controls playsinline width="800" height="450"></video>
                @else
                <img src="{{ (str_starts_with($url, 'http') || str_starts_with($url, '/')) ? $url : asset($url) }}" alt="{{ is_array($noticia->altExtras) ? ($noticia->altExtras[$idx] ?? '') : '' }}" width="800" height="800" decoding="async">
                @endif
            </figure>
            @endif
        @endforeach
    </div>
    @endif
    @if(!empty($noticia->videoClip) && !empty($noticia->linkVideoClip))
    @php
        $videoSrc = $noticia->linkVideoClip;
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoSrc, $m)) {
            $videoSrc = 'https://www.youtube.com/embed/' . $m[1];
        }
    @endphp
    <div class="video-clip">
        <iframe src="{{ $videoSrc }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy" title="Video: {{ $noticia->titulo }}"></iframe>
    </div>
    @endif
    <a href="{{ route('noticias.index') }}" class="volver-noticias-link">← Volver a noticias</a>
</article>
@endsection
