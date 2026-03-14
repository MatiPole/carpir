<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>Panel de Administración | Carpir</title>
    <style>
        :root { --accent-red: #ff6b6b; --dark-navy: #0a1628; --navy: #152238; --ff-sansation: 'Sansation', sans-serif; --ff-montserrat: 'Montserrat', sans-serif; }
        .admin-page { min-height: 100vh; background: #f5f5f5; padding: 2rem; font-family: var(--ff-montserrat); }
        .admin-header { background: white; padding: 2rem; border-radius: 15px; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .admin-header h1 { font-family: var(--ff-sansation); font-size: 2rem; color: var(--accent-red); margin: 0 0 0.5rem; }
        .admin-header p { color: #666; font-size: 1rem; margin: 0; }
        .logout-btn { padding: 0.75rem 1.5rem; background: #dc3545; color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; }
        .logout-btn:hover { background: #c82333; transform: translateY(-2px); }
        .admin-tabs { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .admin-tab { padding: 0.75rem 1.5rem; border: none; border-radius: 10px; background: #e0e0e0; color: #555; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.3s ease; }
        .admin-tab:hover { background: #d0d0d0; color: #333; }
        .admin-tab.active { background: linear-gradient(135deg, var(--accent-red), #ff4d2e); color: white; box-shadow: 0 2px 10px rgba(255,107,107,0.3); }
        .admin-content { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .admin-toolbar { margin-bottom: 2rem; }
        .create-button { padding: 1rem 2rem; background: linear-gradient(135deg, var(--accent-red), #ff4d2e); color: white; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(255,107,107,0.4); }
        .create-button:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,107,107,0.5); }
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }
        .noticias-table { width: 100%; border-collapse: collapse; font-family: var(--ff-montserrat); }
        .noticias-table thead { background: linear-gradient(135deg, var(--dark-navy), var(--navy)); color: white; }
        .noticias-table th { padding: 1rem; text-align: left; font-weight: 600; font-size: 0.95rem; }
        .noticias-table td { padding: 1rem; border-bottom: 1px solid #e0e0e0; color: #333; }
        .noticias-table tbody tr:hover { background: #f9f9f9; }
        .empty-state { text-align: center; padding: 3rem; color: #999; font-style: italic; }
        .actions-cell { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .edit-button { padding: 0.5rem 1rem; border: none; border-radius: 6px; background: #007bff; color: white; font-weight: 600; font-size: 0.875rem; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.3s ease; }
        .edit-button:hover { background: #0056b3; transform: translateY(-1px); }
        .delete-button { padding: 0.5rem 1rem; border: none; border-radius: 6px; background: #dc3545; color: white; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: all 0.3s ease; }
        .delete-button:hover { background: #c82333; transform: translateY(-1px); }
        .activate-button { padding: 0.5rem 1rem; border: none; border-radius: 6px; background: #28a745; color: white; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: all 0.3s ease; }
        .activate-button:hover { background: #218838; transform: translateY(-1px); }
        .integrante-thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; display: block; }
        .integrante-thumb-placeholder { display: inline-block; width: 48px; height: 48px; line-height: 48px; text-align: center; background: #eee; border-radius: 8px; color: #999; }
        .integrante-inactivo { opacity: 0.65; background: #f9f9f9; }
        .integrante-inactivo td { color: #777; }
        .success { background: #d4edda; color: #155724; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #c3e6cb; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; font-size: 0.95rem; }
        .form-group input, .form-group textarea { width: 100%; max-width: 500px; padding: 0.875rem; border: 2px solid #e0e0e0; border-radius: 10px; font-family: var(--ff-montserrat); font-size: 1rem; background: #fff; color: #2d2d2d; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: var(--accent-red); box-shadow: 0 0 0 3px rgba(255,107,107,0.1); }
        trix-editor { border: 2px solid #e0e0e0; border-radius: 10px; padding: 0.875rem; min-height: 120px; max-width: 600px; background: #fff; color: #2d2d2d; }
        trix-toolbar .trix-button-group { border-color: #dee2e6; background: #f8f9fa; }
        .img-upload-wrap { display: flex; align-items: flex-start; gap: 1rem; flex-wrap: wrap; }
        .img-thumb { width: 140px; height: 90px; object-fit: cover; border-radius: 12px; border: 2px solid #e0e0e0; }
        .img-thumb-empty { width: 140px; height: 90px; background: #f5f5f5; border: 2px dashed #ddd; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #666; font-size: 0.85rem; }
        .img-upload-btn { padding: 0.5rem 1rem; background: #0066cc; color: white; border: none; border-radius: 6px; font-size: 0.9rem; cursor: pointer; }
        .img-upload-btn:hover { filter: brightness(1.1); }
        .img-upload-btn:disabled { opacity: 0.6; cursor: not-allowed; }
        input[type="file"].hide-input { display: none; }
        .admin-content .table-link { color: #0066cc; font-size: 0.85rem; word-break: break-all; }
        .admin-content .table-link:hover { text-decoration: underline; }
        .escuchanos-url-cell { max-width: 280px; }
        .nosotros-config-actions .save-button { padding: 0.875rem 2rem; border: none; border-radius: 10px; background: linear-gradient(135deg, var(--accent-red), #ff4d2e); color: white; font-weight: 700; cursor: pointer; box-shadow: 0 4px 15px rgba(255,107,107,0.4); }
        .nosotros-config-actions .save-button:hover { transform: translateY(-2px); }
        @media (max-width: 768px) { .admin-page { padding: 1rem; } .admin-header { flex-direction: column; gap: 1rem; align-items: flex-start; } .noticias-table th, .noticias-table td { padding: 0.75rem 0.5rem; } .actions-cell { flex-direction: column; } }
    </style>
</head>
<body class="admin-page">
    <div class="admin-header">
        <div>
            <h1>Panel de Administración</h1>
            <p>Bienvenido, {{ Auth::user()->username ?? 'admin' }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" class="logout-btn">Cerrar sesión</button>
        </form>
    </div>

    <div class="admin-tabs">
        <button type="button" class="admin-tab active" data-tab="fechas">Fechas</button>
        <button type="button" class="admin-tab" data-tab="noticias">Noticias</button>
        <button type="button" class="admin-tab" data-tab="nosotros">Nosotros</button>
        <button type="button" class="admin-tab" data-tab="escuchanos">Escuchanos</button>
        <button type="button" class="admin-tab" data-tab="integrantes">Integrantes</button>
    </div>

    <div class="admin-content">
        @if(session('success'))
        <p class="success">{{ session('success') }}</p>
        @endif

        <div id="pane-fechas" class="tab-pane active">
            <div class="admin-toolbar">
                <a href="{{ route('admin.fechas.create') }}" class="create-button">+ Nueva fecha</a>
            </div>
            <div class="noticias-table-container" style="overflow-x:auto">
                <table class="noticias-table">
                    <thead><tr><th>Fecha</th><th>Locación</th><th>Dirección</th><th>Acciones</th></tr></thead>
                    <tbody>
                        @forelse($fechas as $f)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($f->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $f->locacion }}</td>
                            <td>{{ Str::limit($f->direccion, 30) }}</td>
                            <td class="actions-cell">
                                <a href="{{ route('admin.fechas.edit', $f->id) }}" class="edit-button">Editar</a>
                                <form method="POST" action="{{ route('admin.fechas.destroy', $f->id) }}" style="display:inline" onsubmit="return confirm('¿Eliminar esta fecha?')">@csrf @method('DELETE')<button type="submit" class="delete-button">Eliminar</button></form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="empty-state">No hay fechas. Agregá una para que aparezca en la página pública.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div id="pane-noticias" class="tab-pane">
            <div class="admin-toolbar">
                <a href="{{ route('admin.noticias.create') }}" class="create-button">+ Nueva Noticia</a>
            </div>
            <table class="noticias-table">
                <thead><tr><th>Título</th><th>Fecha</th><th>Video</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse($noticias as $n)
                    <tr>
                        <td>{{ Str::limit($n->titulo, 40) }}</td>
                        <td>{{ $n->fecha }}</td>
                        <td>{{ $n->videoClip ? 'Sí' : 'No' }}</td>
                        <td class="actions-cell">
                            <a href="{{ route('admin.noticias.edit', $n->id) }}" class="edit-button">Editar</a>
                            <form method="POST" action="{{ route('admin.noticias.destroy', $n->id) }}" style="display:inline" onsubmit="return confirm('¿Eliminar esta noticia?')">@csrf @method('DELETE')<button type="submit" class="delete-button">Eliminar</button></form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="empty-state">No hay noticias.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="pane-nosotros" class="tab-pane">
            <form method="POST" action="{{ route('admin.nosotros.update') }}" id="form-nosotros">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Descripción principal *</label>
                    <input type="hidden" name="descripcion" id="nosotros-descripcion" value="{{ old('descripcion', $nosotros->descripcion ?? '') }}" required>
                    <trix-editor input="nosotros-descripcion" placeholder="Escribí la descripción. Podés usar negrita, cursiva, listas, etc."></trix-editor>
                </div>
                <div class="form-group">
                    <label>Texto "Ver más" (historia adicional)</label>
                    <input type="hidden" name="descripcion_extra" id="nosotros-descripcion-extra" value="{{ old('descripcion_extra', $nosotros->descripcion_extra ?? '') }}">
                    <trix-editor input="nosotros-descripcion-extra" placeholder="Texto que se muestra al expandir «Ver más»."></trix-editor>
                </div>
                <div class="form-group">
                    <label>Imagen de portada</label>
                    <input type="hidden" name="imagen_portada" id="nosotros-imagen-path" value="{{ $nosotros->imagen_portada ?? '' }}">
                    <div class="img-upload-wrap">
                        <div id="nosotros-imagen-thumb-wrap">
                            <div class="img-thumb-empty" id="nosotros-imagen-empty" style="{{ !empty($nosotros->imagen_portada) ? 'display:none' : '' }}">Sin imagen</div>
                            <img src="{{ !empty($nosotros->imagen_portada) ? ((str_starts_with($nosotros->imagen_portada, 'http') || str_starts_with($nosotros->imagen_portada, '/')) ? $nosotros->imagen_portada : asset($nosotros->imagen_portada)) : '' }}" alt="" class="img-thumb" id="nosotros-imagen-thumb" style="{{ empty($nosotros->imagen_portada) ? 'display:none' : '' }}">
                        </div>
                        <div>
                            <button type="button" class="img-upload-btn" id="nosotros-btn-imagen">Elegir imagen</button>
                            <input type="file" accept="image/*" id="nosotros-input-imagen" class="hide-input">
                        </div>
                    </div>
                </div>
                <div class="nosotros-config-actions">
                    <button type="submit" class="save-button">Guardar configuración</button>
                </div>
            </form>
        </div>

        <div id="pane-escuchanos" class="tab-pane">
            <div class="admin-toolbar">
                <a href="{{ route('admin.escuchanos.create') }}" class="create-button">+ Nuevo item de Spotify</a>
            </div>
            <table class="noticias-table">
                <thead><tr><th>Orden</th><th>Título</th><th>Enlace</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse($escuchanos as $e)
                    <tr>
                        <td>{{ $e->orden }}</td>
                        <td>{{ $e->titulo ?? '—' }}</td>
                        <td class="escuchanos-url-cell"><a href="{{ $e->embed_url }}" target="_blank" rel="noopener" class="table-link">{{ Str::limit($e->embed_url, 50) }}</a></td>
                        <td class="actions-cell">
                            <a href="{{ route('admin.escuchanos.edit', $e->id) }}" class="edit-button">Editar</a>
                            <form method="POST" action="{{ route('admin.escuchanos.destroy', $e->id) }}" style="display:inline" onsubmit="return confirm('¿Eliminar?')">@csrf @method('DELETE')<button type="submit" class="delete-button">Eliminar</button></form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="empty-state">No hay items. Agregá un enlace de Spotify.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div id="pane-integrantes" class="tab-pane">
            <div class="admin-toolbar">
                <a href="{{ route('admin.integrantes.create') }}" class="create-button">+ Nuevo integrante</a>
            </div>
            <table class="noticias-table">
                <thead><tr><th>Foto</th><th>Nombre</th><th>Rol</th><th>Orden</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    @forelse($integrantes as $i)
                    <tr class="{{ $i->activo ? '' : 'integrante-inactivo' }}">
                        <td>@if($i->imagen)<img src="{{ (str_starts_with($i->imagen, 'http') || str_starts_with($i->imagen, '/')) ? $i->imagen : asset($i->imagen) }}" alt="" class="integrante-thumb">@else<span class="integrante-thumb-placeholder">—</span>@endif</td>
                        <td>{{ $i->nombre }}</td>
                        <td>{{ $i->rol }}</td>
                        <td>{{ $i->orden }}</td>
                        <td>{{ $i->activo ? 'Activo' : 'Inactivo' }}</td>
                        <td class="actions-cell">
                            <a href="{{ route('admin.integrantes.edit', $i->id) }}" class="edit-button">Editar</a>
                            <form method="POST" action="{{ route('admin.integrantes.toggle', $i->id) }}" style="display:inline">@csrf<button type="submit" class="{{ $i->activo ? 'delete-button' : 'activate-button' }}">{{ $i->activo ? 'Inactivar' : 'Activar' }}</button></form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="empty-state">No hay integrantes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function activateTab(tabName) {
            var tabBtn = document.querySelector('.admin-tab[data-tab="' + tabName + '"]');
            if (!tabBtn) return;
            document.querySelectorAll('.admin-tab').forEach(function(b){ b.classList.remove('active'); });
            document.querySelectorAll('.tab-pane').forEach(function(p){ p.classList.remove('active'); });
            tabBtn.classList.add('active');
            var pane = document.getElementById('pane-' + tabName);
            if (pane) pane.classList.add('active');
        }
        document.querySelectorAll('.admin-tab').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var tab = this.getAttribute('data-tab');
                var successEl = document.querySelector('.admin-content .success');
                if (successEl) successEl.remove();
                activateTab(tab);
                window.location.hash = tab;
            });
        });
        (function() {
            var hash = window.location.hash.replace(/^#/, '');
            if (hash && ['fechas','noticias','nosotros','escuchanos','integrantes'].indexOf(hash) !== -1) {
                activateTab(hash);
            }
        })();
        (function() {
            var pathInput = document.getElementById('nosotros-imagen-path');
            var thumb = document.getElementById('nosotros-imagen-thumb');
            var thumbEmpty = document.getElementById('nosotros-imagen-empty');
            var btn = document.getElementById('nosotros-btn-imagen');
            var fileInput = document.getElementById('nosotros-input-imagen');
            var form = document.getElementById('form-nosotros');
            var saveBtn = form ? form.querySelector('button[type="submit"]') : null;
            var pendingNosotrosFile = null;
            var uploadUrl = '{{ route("upload.store") }}';
            var csrfToken = document.querySelector('meta[name="csrf-token"]');
            csrfToken = csrfToken ? csrfToken.getAttribute('content') : '{{ csrf_token() }}';
            if (!pathInput || !btn) return;
            function showThumbFromUrl(url) {
                thumb.src = url.startsWith('http') || url.startsWith('/') ? url : (window.location.origin + (url.startsWith('/') ? '' : '/') + url);
                thumb.style.display = 'block';
                if (thumbEmpty) thumbEmpty.style.display = 'none';
            }
            function showThumbFromFile(file) {
                thumb.src = URL.createObjectURL(file);
                thumb.style.display = 'block';
                if (thumbEmpty) thumbEmpty.style.display = 'none';
            }
            btn.addEventListener('click', function() { fileInput.click(); });
            fileInput.addEventListener('change', function() {
                var file = this.files[0];
                if (!file) return;
                pendingNosotrosFile = file;
                pathInput.value = '';
                showThumbFromFile(file);
                this.value = '';
            });
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!pendingNosotrosFile) return;
                    e.preventDefault();
                    if (saveBtn) { saveBtn.disabled = true; saveBtn.textContent = 'Guardando...'; }
                    var formData = new FormData();
                    formData.append('file', pendingNosotrosFile);
                    formData.append('type', 'img');
                    fetch(uploadUrl, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                    }).then(function(r) { return r.json(); })
                    .then(function(data) {
                        if (data.url) {
                            pathInput.value = data.url;
                            pendingNosotrosFile = null;
                            form.submit();
                        } else if (data.error) throw new Error(data.error);
                    }).catch(function(err) {
                        if (saveBtn) { saveBtn.disabled = false; saveBtn.textContent = 'Guardar configuración'; }
                        alert(err.message || 'Error al subir.');
                    });
                });
            }
        })();
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.8/dist/trix.css">
    <script src="https://cdn.jsdelivr.net/npm/trix@2.0.8/dist/trix.umd.min.js"></script>
</body>
</html>
