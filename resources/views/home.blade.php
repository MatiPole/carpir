@extends('layouts.app')

@section('title', 'Carpir | Banda de Rock Indie')
@section('meta_description', 'Carpir es una banda de rock indie de Buenos Aires. Escuchá nuestra música, mirá próximas fechas y enterate de las últimas noticias.')
@section('canonical', route('home'))

@section('content')
<div class="home">
    <section id="nosotros" class="nosotros-section section">
        <h2 class="section-title">Nosotros</h2>
        @if(! empty($lcpImagePreload))
        <div class="nosotros-portada">
            <img src="{{ $lcpImagePreload }}" alt="Carpir — imagen de la banda" decoding="async" fetchpriority="high" width="1200" height="500" sizes="(max-width: 768px) 100vw, min(900px, 100vw)">
        </div>
        @endif
        <div class="nosotros-texto">
            <div class="nosotros-desc">{!! $nosotros->descripcion ?? '<p>Carpir es un proyecto que comienza en el 2018 y termina de consolidarse a finales del 2021.</p>' !!}</div>
            @if(!empty($nosotros->descripcion_extra))
            <details class="nosotros-mas">
                <summary>Ver más</summary>
                <div class="nosotros-extra">{!! $nosotros->descripcion_extra !!}</div>
            </details>
            @endif
        </div>
        <div class="integrantes-grid">
            @foreach($integrantes as $i)
            <div class="integrante-card">
                @if($i->imagen)
                <img src="{{ (str_starts_with($i->imagen, 'http') || str_starts_with($i->imagen, '/')) ? $i->imagen : asset($i->imagen) }}" alt="{{ $i->nombre }}" width="200" height="200" sizes="(max-width: 600px) 150px, 200px" loading="lazy" decoding="async">
                @endif
                <h3>{{ $i->nombre }}</h3>
                <p>{{ $i->rol }}</p>
            </div>
            @endforeach
        </div>
    </section>

    <section id="proximas-fechas" class="fechas-preview-section section">
        <h2 class="section-title">Próximas fechas</h2>
        @if($fechas->isEmpty())
        <div class="fechas-preview-empty">
            <p>No hay fechas próximas por el momento.</p>
            <a href="{{ route('fechas.index') }}" class="fechas-preview-link">Ver fechas</a>
        </div>
        @else
        <div class="fechas-preview-wrapper">
            <table class="fechas-preview-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Locación</th>
                        <th>Dirección</th>
                        <th>Horario</th>
                        <th>Costo</th>
                        <th>Entradas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fechas as $f)
                    <tr>
                        <td data-label="Fecha">{{ \Carbon\Carbon::parse($f->fecha)->format('d/m/Y') }}</td>
                        <td data-label="Locación">{{ $f->locacion ?: '—' }}</td>
                        <td data-label="Dirección">{{ $f->direccion ?: '—' }}</td>
                        <td data-label="Horario">{{ $f->horario ?: '—' }}</td>
                        <td data-label="Costo">{{ $f->costo ?: '—' }}</td>
                        <td data-label="Entradas">
                            @if($f->link_entradas)
                            <a href="{{ $f->link_entradas }}" target="_blank" rel="noopener noreferrer" class="fechas-preview-btn">Entradas</a>
                            @else
                            —
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('fechas.index') }}" class="fechas-preview-link fechas-preview-link-all">Ver todas las fechas</a>
        </div>
        @endif
    </section>

    <section id="escuchanos" class="escuchanos-section section">
        <h2 class="section-title">Escuchanos</h2>
        <div class="reproductor-container">
            @foreach($escuchanos as $it)
            <div class="reproductor">
                @if($it->titulo)<h3>{{ $it->titulo }}</h3>@endif
                <iframe data-src="{{ $it->embed_url }}" width="100%" height="{{ $it->titulo ? 380 : 352 }}" style="border:0;border-radius:12px;max-width:100%;display:block" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" title="{{ $it->titulo ?? 'Spotify' }}"></iframe>
            </div>
            @endforeach
        </div>
        <a href="{{ route('escuchanos.index') }}" class="escuchanos-ver-mas">Ver más</a>
    </section>

    <section id="noticias" class="noticias-preview-section section">
        <h2 class="section-title">Noticias</h2>
        @if($noticias->isEmpty())
        <p class="noticias-preview-empty">No hay noticias aún.</p>
        @else
        @php
            $ultima = $noticias->first();
            $imgs = is_array($ultima->img) ? $ultima->img : [];
            $alts = is_array($ultima->alt) ? $ultima->alt : [];
            $isVideoUrl = function($u) { return $u && preg_match('/\.(mp4|webm|ogg|mov)(\?|$)/i', $u); };
        @endphp
        <div class="noticia-preview-container">
            <article class="noticia-preview-content">
                <h3>{{ $ultima->titulo }}<span class="fecha-badge">{{ $ultima->fecha }}</span></h3>
                <div class="noticia-texto-expandable" data-noticia-expandable>
                    <div class="noticia-texto noticia-texto-inner">{!! $ultima->noticia !!}</div>
                    <button type="button" class="noticia-ver-mas-btn" aria-expanded="false">Ver más</button>
                </div>
            </article>
            <div class="noticia-carousel" data-carousel="noticia-preview">
                <button type="button" class="carousel-button prev" aria-label="Anterior">‹</button>
                <div class="carousel-images">
                    @foreach($imgs as $idx => $url)
                    @if($url)
                    @if($isVideoUrl($url))
                    <video src="{{ (str_starts_with($url, 'http') || str_starts_with($url, '/')) ? $url : asset($url) }}" controls class="carousel-image {{ $idx === 0 ? 'active' : '' }}" width="800" height="450" playsinline></video>
                    @else
                    <img src="{{ (str_starts_with($url, 'http') || str_starts_with($url, '/')) ? $url : asset($url) }}" alt="{{ $alts[$idx] ?? '' }}" class="carousel-image {{ $idx === 0 ? 'active' : '' }}" width="800" height="800" sizes="(max-width: 768px) 100vw, 50vw" @if($idx > 0) loading="lazy" @endif decoding="async">
                    @endif
                    @endif
                    @endforeach
                </div>
                <button type="button" class="carousel-button next" aria-label="Siguiente">›</button>
                @if(count($imgs) > 1)
                <div class="carousel-indicators">
                    @foreach($imgs as $idx => $url)
                    @if($url)<button type="button" class="indicator {{ $idx === 0 ? 'active' : '' }}" aria-label="Ir a {{ $isVideoUrl($url) ? 'video' : 'imagen' }} {{ $idx + 1 }}"></button>@endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        <a href="{{ route('noticias.index') }}" class="ver-mas-section-link escuchanos-ver-mas">Más Noticias</a>
        @push('scripts')
        <script src="{{ asset('assets/noticia-expandable.js') }}?v={{ filemtime(public_path('assets/noticia-expandable.js')) }}"></script>
        <script>
        (function(){
          var c = document.querySelector('.noticia-carousel[data-carousel="noticia-preview"]');
          if (!c) return;
          var images = c.querySelectorAll('.carousel-image');
          var indicators = c.querySelectorAll('.indicator');
          if (images.length <= 1) return;
          var idx = 0;
          function show(i) {
            idx = (i + images.length) % images.length;
            images.forEach(function(img, k) { img.classList.toggle('active', k === idx); });
            indicators.forEach(function(ind, k) { ind.classList.toggle('active', k === idx); });
          }
          c.querySelector('.carousel-button.prev').addEventListener('click', function() { show(idx - 1); });
          c.querySelector('.carousel-button.next').addEventListener('click', function() { show(idx + 1); });
          indicators.forEach(function(ind, k) { ind.addEventListener('click', function() { show(k); }); });
          setInterval(function() { show(idx + 1); }, 3000);
        })();
        </script>
        @endpush
        @endif
    </section>

    <section id="contacto" class="section contacto-section">
        <div class="contacto-container">
            <h2 class="section-title">Contacto</h2>
            <p class="contacto-intro">¿Querés escribirnos por fechas, prensa o colaboración? Completá el formulario y te respondemos a la brevedad.</p>
            @if(session('contact_success'))
                <div class="success-message" role="status">{{ session('contact_success') }}</div>
            @endif
            @if(session('contact_error'))
                <div class="contact-error-message" role="alert">{{ session('contact_error') }}</div>
            @endif
            <div class="contacto-content">
                <div class="contacto-panel">
                    <div class="contacto-card contacto-info-card">
                        <h3>Hablemos</h3>
                        <p>Usá este canal para consultas, contrataciones, prensa o cualquier mensaje relacionado con la banda.</p>
                        <div class="contacto-info-list">
                            <p><strong>Email:</strong> <a href="mailto:carpirok@gmail.com">carpirok@gmail.com</a></p>
                            <p><strong>Ubicación:</strong> Buenos Aires, Argentina</p>
                            <p><strong>Respuesta:</strong> Habitualmente dentro de 24 a 48 hs.</p>
                        </div>
                    </div>

                    <form class="contacto-form contacto-card" method="POST" action="{{ route('contacto.store') }}" id="contactForm">
                        @csrf
                        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="contacto-honeypot" aria-hidden="true">

                        <div class="contacto-grid">
                            <div class="form-control {{ $errors->has('name') ? 'error' : '' }}">
                                <label for="contact-name">Nombre <span class="required">*</span></label>
                                <input type="text" id="contact-name" name="name" value="{{ old('name') }}" placeholder="Tu nombre" autocomplete="given-name" required>
                                @error('name')<small>{{ $message }}</small>@enderror
                            </div>
                            <div class="form-control {{ $errors->has('lastname') ? 'error' : '' }}">
                                <label for="contact-lastname">Apellido <span class="required">*</span></label>
                                <input type="text" id="contact-lastname" name="lastname" value="{{ old('lastname') }}" placeholder="Tu apellido" autocomplete="family-name" required>
                                @error('lastname')<small>{{ $message }}</small>@enderror
                            </div>
                            <div class="form-control {{ $errors->has('email') ? 'error' : '' }}">
                                <label for="contact-email">Email <span class="required">*</span></label>
                                <input type="email" id="contact-email" name="email" value="{{ old('email') }}" placeholder="tu@email.com" autocomplete="email" inputmode="email" required>
                                @error('email')<small>{{ $message }}</small>@enderror
                            </div>
                            <div class="form-control {{ $errors->has('phone') ? 'error' : '' }}">
                                <label for="contact-phone">Teléfono <span class="required">*</span></label>
                                <input type="tel" id="contact-phone" name="phone" value="{{ old('phone') }}" placeholder="+54 11 2345 6789" autocomplete="tel" inputmode="tel" required>
                                @error('phone')<small>{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="form-control {{ $errors->has('comments') ? 'error' : '' }}">
                            <label for="contact-comments">Mensaje <span class="required">*</span></label>
                            <textarea id="contact-comments" name="comments" rows="6" placeholder="Escribinos tu mensaje" required>{{ old('comments') }}</textarea>
                            <span class="contacto-help">Solo necesitamos que el mensaje no esté vacío.</span>
                            @error('comments')<small>{{ $message }}</small>@enderror
                        </div>

                        <div class="contacto-actions">
                            <button type="submit" class="submit-button">
                                <span class="submit-label">Enviar mensaje</span>
                            </button>
                            <p class="contacto-privacy">Tus datos se usan únicamente para responder esta consulta.</p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="info-contacto-seccion">
                <p>carpirok@gmail.com</p>
                <p>Buenos Aires, Argentina</p>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('contactForm');
    if (!form) return;

    form.addEventListener('submit', function () {
        var button = form.querySelector('button[type="submit"]');
        if (!button) return;
        button.disabled = true;
        button.classList.add('is-loading');
        var label = button.querySelector('.submit-label');
        if (label) label.textContent = 'Enviando...';
    });
})();
</script>
@endpush
