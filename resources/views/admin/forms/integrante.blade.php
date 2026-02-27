@extends('layouts.admin-form')

@section('title', ($integrante ? 'Editar integrante' : 'Nuevo integrante') . ' | Carpir Admin')
@section('modal_title', $integrante ? 'Editar integrante' : 'Nuevo integrante')

@push('styles')
<style>
    .admin-form-body .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .admin-form-body .img-upload-wrap { display: flex; align-items: flex-start; gap: 1rem; flex-wrap: wrap; }
    .admin-form-body .img-thumb { width: 120px; height: 120px; object-fit: cover; border-radius: 12px; border: 2px solid #e0e0e0; }
    .admin-form-body .img-thumb-empty { width: 120px; height: 120px; background: #f5f5f5; border: 2px dashed #ddd; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #666; font-size: 0.85rem; }
    .admin-form-body .img-upload-btn { padding: 0.5rem 1rem; background: #0066cc; color: white; border: none; border-radius: 6px; font-size: 0.875rem; font-weight: 600; cursor: pointer; }
    .admin-form-body .img-upload-btn:hover:not(:disabled) { filter: brightness(1.1); }
    .admin-form-body .img-upload-btn:disabled { opacity: 0.6; cursor: not-allowed; }
    .admin-form-body .checkbox-group { flex-direction: row; align-items: center; }
    .admin-form-body .checkbox-group input[type="checkbox"] { width: 20px; height: 20px; margin-right: 0.5rem; }
    @media (max-width: 600px) { .admin-form-body .form-row { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<form method="POST" action="{{ $integrante ? route('admin.integrantes.update', $integrante->id) : route('admin.integrantes.store') }}" id="form-integrante">
    @csrf
    @if($integrante) @method('PUT') @endif

    <div class="form-row">
        <div class="form-group">
            <label for="nombre">Nombre *</label>
            <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $integrante->nombre ?? '') }}" required>
        </div>
        <div class="form-group">
            <label for="rol">Rol *</label>
            <input id="rol" type="text" name="rol" value="{{ old('rol', $integrante->rol ?? '') }}" required>
        </div>
    </div>

    <div class="form-group">
        <label>Foto</label>
        <input type="hidden" name="imagen" id="imagen-path" value="{{ old('imagen', $integrante->imagen ?? '') }}">
        <div class="img-upload-wrap">
            <div id="imagen-thumb-wrap">
                <div class="img-thumb-empty" id="imagen-thumb-empty" style="{{ !empty($integrante->imagen) ? 'display:none' : '' }}">Sin imagen</div>
                <img src="{{ !empty($integrante->imagen) ? ((str_starts_with($integrante->imagen, 'http') || str_starts_with($integrante->imagen, '/')) ? $integrante->imagen : asset($integrante->imagen)) : '' }}" alt="Foto" class="img-thumb" id="imagen-thumb" style="{{ empty($integrante->imagen) ? 'display:none' : '' }}">
            </div>
            <div>
                <button type="button" class="img-upload-btn" id="btn-elegir-imagen">Elegir imagen</button>
                <input type="file" accept="image/*" id="input-imagen-file" style="position:absolute;width:0;height:0;opacity:0;">
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="orden">Orden</label>
            <input id="orden" type="number" name="orden" value="{{ old('orden', $integrante->orden ?? 0) }}">
        </div>
        @if($integrante)
        <div class="form-group checkbox-group">
            <label>
                <input type="checkbox" name="activo" value="1" {{ ($integrante->activo ?? true) ? 'checked' : '' }}>
                Activo (visible en la web)
            </label>
        </div>
        @endif
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.index') }}" class="cancel-button">Cancelar</a>
        <button type="submit" class="save-button">Guardar</button>
    </div>
</form>

@push('scripts')
<script>
(function() {
    var pathInput = document.getElementById('imagen-path');
    var thumb = document.getElementById('imagen-thumb');
    var thumbEmpty = document.getElementById('imagen-thumb-empty');
    var btn = document.getElementById('btn-elegir-imagen');
    var fileInput = document.getElementById('input-imagen-file');
    if (!btn) return;
    function showThumb(url) {
        thumb.src = url.startsWith('http') || url.startsWith('/') ? url : (window.location.origin + (url.startsWith('/') ? '' : '/') + url);
        thumb.style.display = 'block';
        if (thumbEmpty) thumbEmpty.style.display = 'none';
    }
    if (thumbEmpty && thumb.src) thumbEmpty.style.display = 'none';
    btn.addEventListener('click', function() { fileInput.click(); });
    fileInput.addEventListener('change', function() {
        var file = this.files[0];
        if (!file) return;
        btn.disabled = true;
        btn.textContent = 'Subiendo…';
        var formData = new FormData();
        formData.append('file', file);
        formData.append('type', 'img');
        fetch('{{ route("upload.store") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.url) { pathInput.value = data.url; showThumb(data.url); }
            else if (data.error) alert(data.error);
        }).catch(function() { alert('Error al subir la imagen.'); })
        .finally(function() {
            btn.disabled = false;
            btn.textContent = 'Elegir imagen';
            fileInput.value = '';
        });
    });
})();
</script>
@endpush
@endsection
