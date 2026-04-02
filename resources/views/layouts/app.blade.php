<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $seoTitle = trim($__env->yieldContent('title', 'Carpir | Banda de Rock Indie'));
        $seoDescription = trim($__env->yieldContent('meta_description', 'Somos Carpir, una banda de rock indie de Buenos Aires. Escuchanos en plataformas digitales y enterate de nuestras noticias y próximas fechas.'));
        $seoCanonical = trim($__env->yieldContent('canonical', url()->current()));
        $seoImage = trim($__env->yieldContent('meta_image', asset('assets/img/carpir-logo.png')));
        $seoType = trim($__env->yieldContent('og_type', 'website'));
        $seoRobots = trim($__env->yieldContent('meta_robots', 'index,follow,max-image-preview:large'));
    @endphp
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $seoRobots }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    <meta property="og:locale" content="es_AR">
    <meta property="og:type" content="{{ $seoType }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:site_name" content="Carpir">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
    <meta name="keywords" content="banda, rock band, Carpir, rock indie, rock nacional, Buenos Aires">
    <meta name="theme-color" content="#0b0f19">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(! empty($lcpImagePreload ?? null))
    <link rel="preload" as="image" href="{{ $lcpImagePreload }}">
    @endif
    @php
        $fontsCss = 'https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@400;500;700&display=swap';
        $appCssHrefs = [];
        if (file_exists(public_path('assets/app.css'))) {
            $appCssHrefs[] = asset('assets/app.css').'?v='.filemtime(public_path('assets/app.css'));
        } elseif (file_exists(public_path('assets/style.css'))) {
            $appCssHrefs[] = asset('assets/style.css').'?v='.filemtime(public_path('assets/style.css'));
        }
    @endphp
    <link href="{{ $fontsCss }}" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="{{ $fontsCss }}" rel="stylesheet"></noscript>
    <title>@yield('title', 'Carpir | Banda de Rock Indie')</title>
    @stack('styles')
    @foreach($appCssHrefs as $cssHref)
    <link rel="stylesheet" href="{{ $cssHref }}" media="print" onload="this.media='all'">
    @endforeach
    @if(!empty($appCssHrefs))
    <noscript>
        @foreach($appCssHrefs as $cssHref)
        <link rel="stylesheet" href="{{ $cssHref }}">
        @endforeach
    </noscript>
    @endif
    @hasSection('structured_data')
        @yield('structured_data')
    @else
    @php
        $defaultSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'MusicGroup',
            'name' => 'Carpir',
            'url' => url('/'),
            'logo' => asset('assets/img/carpir-logo.png'),
            'image' => asset('assets/img/carpir-logo.png'),
            'sameAs' => [
                'https://open.spotify.com/intl-es/artist/5NzTQJXFKyAX63I3Q7Or5y?si=gqrmldWbS2uDOtEv5xCnPw',
                'https://www.instagram.com/carpirok/',
            ],
        ];
    @endphp
    <script type="application/ld+json">
        {!! json_encode($defaultSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
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
                <li class="nav-item" role="none"><a href="{{ url('/#proximas-fechas') }}" class="nav-link" role="menuitem" @click="navOpen = false">Fechas</a></li>
                <li class="nav-item" role="none"><a href="{{ url('/#escuchanos') }}" class="nav-link" role="menuitem" @click="navOpen = false">Escuchanos</a></li>
                <li class="nav-item" role="none"><a href="{{ url('/#noticias') }}" class="nav-link" role="menuitem" @click="navOpen = false">Noticias</a></li>
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
