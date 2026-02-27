<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Somos Carpir, una banda de rock indie de Buenos Aires. Nuestro material puede escucharse en todas las plataformas">
    <meta property="og:title" content="Carpir | Banda de rock indie">
    <meta property="og:image" content="{{ asset('assets/img/carpir-logo.png') }}">
    <meta name="keywords" content="banda, rock band, Carpir, rock indie, rock nacional, Buenos Aires">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <title>@yield('title', 'Carpir | Banda de Rock Indie')</title>
    @stack('styles')
    @if(file_exists(public_path('assets/app.css')))
    <link rel="stylesheet" href="{{ asset('assets/app.css') }}">
    @elseif(file_exists(public_path('assets/style.css')))
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    @endif
</head>
<body>
    @unless(request()->routeIs('login') || request()->routeIs('admin.index'))
    <header class="header" id="site-header"
            x-data="{ navOpen: false }"
            x-effect="document.body.style.overflow = navOpen ? 'hidden' : ''; document.documentElement.style.overflow = navOpen ? 'hidden' : ''"
            @keydown.escape.window="navOpen = false"
            @resize.window="if (window.innerWidth > 768) navOpen = false"
            :class="{ 'header--menu-open': navOpen }">
        <nav class="navbar" aria-label="Navegación principal">
            <div class="navbar-container">
                <a href="{{ route('home') }}" class="navbar-brand">
                    <img src="{{ asset('assets/img/carpir-logo.png') }}" alt="Logo Carpir" class="logo-header">
                </a>
                <button type="button"
                        class="navbar-toggler"
                        :class="{ 'active': navOpen }"
                        :aria-label="navOpen ? 'Cerrar menú' : 'Abrir menú'"
                        :aria-expanded="navOpen"
                        aria-controls="navbar-nav"
                        @click="navOpen = !navOpen">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="navbar-backdrop"
                 :aria-hidden="!navOpen"
                 tabindex="-1"
                 @click="navOpen = false"></div>
            <ul class="navbar-nav" id="navbar-nav" role="menubar" :class="{ 'active': navOpen }">
                <li class="nav-item" role="none"><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" role="menuitem" @click="navOpen = false">Home</a></li>
                <li class="nav-item" role="none"><a href="{{ url('/#nosotros') }}" class="nav-link" role="menuitem" @click="navOpen = false">Nosotros</a></li>
                <li class="nav-item" role="none"><a href="{{ url('/#escuchanos') }}" class="nav-link" role="menuitem" @click="navOpen = false">Escuchanos</a></li>
                <li class="nav-item" role="none"><a href="{{ route('noticias.index') }}" class="nav-link {{ request()->routeIs('noticias.*') ? 'active' : '' }}" role="menuitem" @click="navOpen = false">Noticias</a></li>
                <li class="nav-item" role="none"><a href="{{ route('fechas.index') }}" class="nav-link {{ request()->routeIs('fechas.index') ? 'active' : '' }}" role="menuitem" @click="navOpen = false">Fechas</a></li>
                <li class="nav-item" role="none"><a href="{{ url('/#contacto') }}" class="nav-link" role="menuitem" @click="navOpen = false">Contacto</a></li>
            </ul>
        </nav>
    </header>
    @endunless

    <main>
        @yield('content')
    </main>

    @unless(request()->routeIs('login') || request()->routeIs('admin.index'))
    <footer class="footer">
        <div class="footer-container">
            <div class="info-contacto">
                <h4>Contacto</h4>
                <ul>
                    <li>carpirok@gmail.com</li>
                    <li>(+54)1167140002</li>
                    <li>Buenos Aires, Argentina</li>
                </ul>
            </div>
            <div class="redes-sociales">
                <a href="https://open.spotify.com/intl-es/artist/5NzTQJXFKyAX63I3Q7Or5y?si=gqrmldWbS2uDOtEv5xCnPw" target="_blank" rel="noopener noreferrer" aria-label="Spotify">
                    <img src="{{ asset('assets/img/spotify.png') }}" alt="Spotify">
                </a>
                <a href="https://www.instagram.com/carpirok/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <img src="{{ asset('assets/img/instagram.png') }}" alt="Instagram">
                </a>
            </div>
            <div class="copyright">
                <small>© {{ date('Y') }} Todos los derechos reservados Carpir</small>
            </div>
        </div>
    </footer>
    @endunless

    @stack('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
</body>
</html>
