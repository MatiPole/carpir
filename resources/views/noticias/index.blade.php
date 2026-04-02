@extends('layouts.app')

@section('title', 'Noticias | Carpir')
@section('meta_description', 'Noticias de Carpir: lanzamientos, novedades, videos y todo lo que pasa con la banda.')
@section('canonical', route('noticias.index'))

@section('content')
<div class="noticias-page">
    <section class="section">
        <h1 class="section-title">Noticias</h1>
        @if($noticias->isEmpty())
        <p class="noticias-empty">No hay noticias por el momento.</p>
        @else
        <div class="noticias-grid">
            @foreach($noticias as $n)
            @php
                $imgs = is_array($n->img) ? array_filter($n->img) : [];
                $alts = is_array($n->alt) ? $n->alt : [];
                $extras = is_array($n->imgExtras) ? array_filter($n->imgExtras) : [];
                $altExtras = is_array($n->altExtras) ? $n->altExtras : [];
                $isVideoUrl = function($u) { return $u && preg_match('/\.(mp4|webm|ogg|mov)(\?|$)/i', $u); };
            @endphp
            <article class="noticia-card" data-noticia-id="{{ $n->id }}">
                <div class="noticia-carousel-container">
                    <div class="noticia-carousel" data-carousel="card-{{ $n->id }}">
                        @if(count($imgs) > 1)
                        <button type="button" class="carousel-button prev" aria-label="Anterior">‹</button>
                        @endif
                        @foreach($imgs as $idx => $url)
                        @if($isVideoUrl($url))
                        <video src="{{ (str_starts_with($url, 'http') || str_starts_with($url, '/')) ? $url : asset($url) }}" controls class="carousel-img {{ $idx === 0 ? 'active' : '' }}" playsinline></video>
                        @else
                        <img src="{{ (str_starts_with($url, 'http') || str_starts_with($url, '/')) ? $url : asset($url) }}" alt="{{ $alts[$idx] ?? '' }}" class="carousel-img {{ $idx === 0 ? 'active' : '' }}">
                        @endif
                        @endforeach
                        @if(count($imgs) > 1)
                        <button type="button" class="carousel-button next" aria-label="Siguiente">›</button>
                        <div class="carousel-indicators">
                            @foreach($imgs as $idx => $url)
                            <button type="button" class="indicator {{ $idx === 0 ? 'active' : '' }}" aria-label="Ir a {{ $isVideoUrl($url) ? 'video' : 'imagen' }} {{ $idx + 1 }}"></button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                <div class="noticia-content">
                    <h2>{{ $n->titulo }}<span class="fecha-badge">{{ $n->fecha }}</span></h2>
                    <div class="noticia-texto-expandable" data-noticia-expandable>
                        <div class="noticia-texto noticia-texto-inner">{!! $n->noticia !!}</div>
                        <button type="button" class="noticia-ver-mas-btn" aria-expanded="false">Ver más</button>
                    </div>
                    <div class="noticia-actions">
                        @if(count($extras) > 0)
                        <button type="button" class="ver-fotos-button" data-img-extras="{{ json_encode($extras) }}" data-alt-extras="{{ json_encode($altExtras) }}">Ver más fotos y videos</button>
                        @endif
                        @if(!empty($n->videoClip) && !empty($n->linkVideoClip))
                        @php
                            $videoUrl = $n->linkVideoClip;
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $videoUrl, $m)) {
                                $videoUrl = 'https://www.youtube.com/watch?v=' . $m[1];
                            }
                        @endphp
                        <a href="{{ $videoUrl }}" target="_blank" rel="noopener noreferrer" class="ver-video-button">Ver video</a>
                        @endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </section>

    <div class="modal-overlay modal-fotos-overlay" id="modal-fotos" aria-hidden="true">
        <div class="modal-content modal-fotos-content" role="dialog" aria-modal="true" aria-label="Galería de fotos y videos">
            <button type="button" class="modal-close" data-modal-close aria-label="Cerrar">×</button>
            <div class="modal-carousel">
                <button type="button" class="modal-carousel-button prev" aria-label="Anterior">‹</button>
                <div class="modal-carousel-content">
                    <img src="" alt="" class="modal-media modal-media-img" id="modal-media-img">
                    <video src="" controls class="modal-media modal-media-video" id="modal-media-video" style="display:none;" playsinline></video>
                </div>
                <button type="button" class="modal-carousel-button next" aria-label="Siguiente">›</button>
                <div class="modal-indicators" id="modal-indicators"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/noticia-expandable.js') }}?v={{ filemtime(public_path('assets/noticia-expandable.js')) }}"></script>
<script>
(function() {
    var modal = document.getElementById('modal-fotos');
    if (!modal) return;
    var mediaImg = document.getElementById('modal-media-img');
    var indicatorsEl = document.getElementById('modal-indicators');
    var currentExtras = [];
    var currentAltExtras = [];
    var currentIndex = 0;

    function openModal(imgExtras, altExtras) {
        currentExtras = imgExtras || [];
        currentAltExtras = altExtras || [];
        currentIndex = 0;
        if (currentExtras.length === 0) return;
        modal.setAttribute('aria-hidden', 'false');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        renderModalSlide();
        buildIndicators();
    }
    function closeModal() {
        modal.setAttribute('aria-hidden', 'true');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
    var mediaVideo = document.getElementById('modal-media-video');
    function imgUrl(u) {
        return u.startsWith('http') || u.startsWith('/') ? u : (window.location.origin + (u.startsWith('/') ? '' : '/') + u);
    }
    function isVideoUrl(u) {
        return u && /\.(mp4|webm|ogg|mov)(\?|$)/i.test(u);
    }
    function renderModalSlide() {
        if (!currentExtras[currentIndex]) return;
        var src = currentExtras[currentIndex];
        var fullUrl = imgUrl(src);
        var isVideo = isVideoUrl(src);
        if (mediaImg) {
            mediaImg.style.display = isVideo ? 'none' : 'block';
            if (!isVideo) { mediaImg.src = fullUrl; mediaImg.alt = currentAltExtras[currentIndex] || ''; }
        }
        if (mediaVideo) {
            mediaVideo.pause();
            mediaVideo.style.display = isVideo ? 'block' : 'none';
            if (isVideo) { mediaVideo.src = fullUrl; mediaVideo.load(); }
        }
        var dots = modal.querySelectorAll('.modal-indicator');
        dots.forEach(function(d, i) { d.classList.toggle('active', i === currentIndex); });
    }
    function buildIndicators() {
        indicatorsEl.innerHTML = '';
        currentExtras.forEach(function(_, i) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'modal-indicator' + (i === 0 ? ' active' : '');
            btn.setAttribute('aria-label', 'Ir a imagen ' + (i + 1));
            btn.addEventListener('click', function() { currentIndex = i; renderModalSlide(); });
            indicatorsEl.appendChild(btn);
        });
    }
    modal.querySelector('[data-modal-close]').addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });
    modal.querySelector('.modal-carousel-button.prev').addEventListener('click', function() {
        currentIndex = (currentIndex - 1 + currentExtras.length) % currentExtras.length;
        renderModalSlide();
    });
    modal.querySelector('.modal-carousel-button.next').addEventListener('click', function() {
        currentIndex = (currentIndex + 1) % currentExtras.length;
        renderModalSlide();
    });
    document.querySelectorAll('.ver-fotos-button').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var imgs = this.getAttribute('data-img-extras');
            var alts = this.getAttribute('data-alt-extras');
            try {
                openModal(JSON.parse(imgs || '[]'), JSON.parse(alts || '[]'));
            } catch (e) {}
        });
    });
})();

document.querySelectorAll('[data-carousel]').forEach(function(container) {
    var images = container.querySelectorAll('.carousel-image, .carousel-img');
    if (images.length <= 1) return;
    var indicators = container.querySelectorAll('.indicator');
    var prev = container.querySelector('.carousel-button.prev, .modal-carousel-button.prev');
    var next = container.querySelector('.carousel-button.next, .modal-carousel-button.next');
    var idx = 0;
    function show(i) {
        idx = (i + images.length) % images.length;
        images.forEach(function(img, k) { img.classList.toggle('active', k === idx); });
        indicators.forEach(function(ind, k) { ind.classList.toggle('active', k === idx); });
    }
    if (prev) prev.addEventListener('click', function() { show(idx - 1); });
    if (next) next.addEventListener('click', function() { show(idx + 1); });
    indicators.forEach(function(ind, k) { ind.addEventListener('click', function() { show(k); }); });
    if (container.getAttribute('data-carousel') === 'noticia-preview') {
        setInterval(function() { show(idx + 1); }, 3000);
    }
});
</script>
@endpush
@endsection
