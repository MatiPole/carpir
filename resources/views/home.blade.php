@extends('layouts.app')

@section('title', 'Carpir | Banda de Rock Indie')

@section('content')
<div class="home">
    <section id="nosotros" class="nosotros-section section">
        <h2 class="section-title">Nosotros</h2>
        @if($nosotros->imagen_portada ?? null)
        <div class="nosotros-portada">
            <img src="{{ (str_starts_with($nosotros->imagen_portada ?? '', 'http') || str_starts_with($nosotros->imagen_portada ?? '', '/')) ? $nosotros->imagen_portada : asset($nosotros->imagen_portada) }}" alt="Carpir">
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
                <img src="{{ (str_starts_with($i->imagen, 'http') || str_starts_with($i->imagen, '/')) ? $i->imagen : asset($i->imagen) }}" alt="{{ $i->nombre }}">
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
                <iframe src="{{ $it->embed_url }}" width="100%" height="{{ $it->titulo ? 380 : 352 }}" style="border-radius:12px" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy" title="{{ $it->titulo ?? 'Spotify' }}"></iframe>
            </div>
            @endforeach
        </div>
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
                <div class="noticia-texto">{!! $ultima->noticia !!}</div>
                <a href="{{ route('noticias.index') }}" class="ver-mas-noticias-button">Más Noticias</a>
            </article>
            <div class="noticia-carousel" data-carousel="noticia-preview">
                <button type="button" class="carousel-button prev" aria-label="Anterior">‹</button>
                <div class="carousel-images">
                    @foreach($imgs as $idx => $url)
                    @if($url)
                    @if($isVideoUrl($url))
                    <video src="{{ (str_starts_with($url, 'http') || str_starts_with($url, '/')) ? $url : asset($url) }}" controls class="carousel-image {{ $idx === 0 ? 'active' : '' }}" playsinline></video>
                    @else
                    <img src="{{ (str_starts_with($url, 'http') || str_starts_with($url, '/')) ? $url : asset($url) }}" alt="{{ $alts[$idx] ?? '' }}" class="carousel-image {{ $idx === 0 ? 'active' : '' }}">
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
        @push('scripts')
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
            @if(session('contact_success'))
                <div class="success-message">{{ session('contact_success') }}</div>
            @endif
            @if(session('contact_error'))
                <div class="contact-error-message">{{ session('contact_error') }}</div>
            @endif
            <div class="contacto-content">
                <form class="contacto-form" method="POST" action="{{ route('contacto.store') }}">
                    @csrf
                    <div class="form-control {{ $errors->has('name') ? 'error' : '' }}">
                        <label for="contact-name">Nombre <span class="required">*</span></label>
                        <input type="text" id="contact-name" name="name" value="{{ old('name') }}" placeholder="Tu nombre" required>
                        @error('name')<small>{{ $message }}</small>@enderror
                    </div>
                    <div class="form-control {{ $errors->has('lastname') ? 'error' : '' }}">
                        <label for="contact-lastname">Apellido <span class="required">*</span></label>
                        <input type="text" id="contact-lastname" name="lastname" value="{{ old('lastname') }}" placeholder="Tu apellido" required>
                        @error('lastname')<small>{{ $message }}</small>@enderror
                    </div>
                    <div class="form-control {{ $errors->has('email') ? 'error' : '' }}">
                        <label for="contact-email">Email <span class="required">*</span></label>
                        <input type="email" id="contact-email" name="email" value="{{ old('email') }}" placeholder="tu@email.com" required>
                        @error('email')<small>{{ $message }}</small>@enderror
                    </div>
                    <div class="form-control {{ $errors->has('phone') ? 'error' : '' }}">
                        <label for="contact-phone">Teléfono <span class="required">*</span></label>
                        <input type="tel" id="contact-phone" name="phone" value="{{ old('phone') }}" placeholder="1123456789" required>
                        @error('phone')<small>{{ $message }}</small>@enderror
                    </div>
                    <div class="form-control {{ $errors->has('comments') ? 'error' : '' }}">
                        <label for="contact-comments">Mensaje <span class="required">*</span></label>
                        <textarea id="contact-comments" name="comments" rows="5" placeholder="Escribe tu mensaje aquí (mínimo 20 caracteres)" required>{{ old('comments') }}</textarea>
                        @error('comments')<small>{{ $message }}</small>@enderror
                    </div>
                    <button type="submit" class="submit-button">Enviar Mensaje</button>
                </form>
            </div>
            <div class="info-contacto-seccion" style="margin-top:2rem">
                <p>carpirok@gmail.com</p>
                <p>(+54)1167140002</p>
                <p>Buenos Aires, Argentina</p>
            </div>
        </div>
    </section>
</div>
@endsection
