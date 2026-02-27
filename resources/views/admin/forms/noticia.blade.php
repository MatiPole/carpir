@extends('layouts.admin-form')

@section('title', ($noticia ? 'Editar noticia' : 'Nueva noticia') . ' | Carpir Admin')
@section('modal_title', $noticia ? 'Editar noticia' : 'Nueva noticia')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.0.8/dist/trix.css">
<style>
    .admin-form-body .img-slot { margin-bottom: 1.25rem; padding: 1rem; background: #f8f9fa; border-radius: 10px; border: 2px solid #e0e0e0; }
    .admin-form-body .img-slot .img-preview-wrap { display: flex; align-items: flex-start; gap: 0.75rem; flex-wrap: wrap; }
    .admin-form-body .img-slot img { max-width: 140px; max-height: 90px; object-fit: cover; border-radius: 8px; border: 2px solid #e0e0e0; }
    .admin-form-body .img-slot .img-url-input { display: none; }
    .admin-form-body .upload-btn { padding: 0.5rem 1rem; background: #0066cc; color: white; border: none; border-radius: 6px; font-size: 0.9rem; cursor: pointer; }
    .admin-form-body .upload-btn:hover { filter: brightness(1.1); }
    .admin-form-body .upload-btn:disabled { opacity: 0.6; cursor: not-allowed; }
    .admin-form-body .img-slot .alt-label { margin-top: 0.5rem; }
    .admin-form-body h3 { font-family: var(--ff-sansation); color: var(--accent-red); margin: 1.5rem 0 0.75rem; font-size: 1.2rem; }
</style>
@endpush

@section('content')
<form method="POST" action="{{ $noticia ? route('admin.noticias.update', $noticia->id) : route('admin.noticias.store') }}" id="form-noticia">
    @csrf
    @if($noticia) @method('PUT') @endif

    <div class="form-group">
        <label for="titulo">Título *</label>
        <input id="titulo" type="text" name="titulo" value="{{ old('titulo', $noticia->titulo ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="fecha">Fecha *</label>
        <input id="fecha" type="text" name="fecha" value="{{ old('fecha', $noticia->fecha ?? date('Y-m-d')) }}" required placeholder="YYYY-MM-DD">
    </div>
    <div class="form-group">
        <label>Noticia (texto) *</label>
        <input type="hidden" name="noticia" id="noticia-body" value="{{ old('noticia', $noticia->noticia ?? '') }}" required>
        <trix-editor input="noticia-body" placeholder="Cuerpo de la noticia. Podés usar negrita, cursiva, listas, enlaces, etc."></trix-editor>
    </div>
    <div class="form-group" style="flex-direction:row;align-items:center;">
        <label style="margin-bottom:0;"><input type="checkbox" name="videoClip" value="1" {{ ($noticia->videoClip ?? false) ? 'checked' : '' }}> Incluye video clip</label>
    </div>
    <div class="form-group">
        <label for="linkVideoClip">Link del video clip (URL de embed)</label>
        <input id="linkVideoClip" type="url" name="linkVideoClip" value="{{ old('linkVideoClip', $noticia->linkVideoClip ?? '') }}" placeholder="https://www.youtube.com/embed/...">
    </div>

    <h3>Imágenes principales</h3>
    @php
        $img = is_array($noticia->img ?? null) ? $noticia->img : [];
        $alt = is_array($noticia->alt ?? null) ? $noticia->alt : [];
        while (count($img) < 3) { $img[] = ''; $alt[] = ''; }
        $img = array_slice($img, 0, 3);
        $alt = array_slice($alt, 0, 3);
    @endphp
    @for($i = 0; $i < 3; $i++)
    <div class="img-slot">
        <label>Imagen o video {{ $i + 1 }}</label>
        <input type="text" name="img[]" value="{{ $img[$i] ?? '' }}" class="img-url-input" aria-hidden="true">
        <div class="img-preview-wrap" data-preview="img-{{ $i }}">
            @if(!empty($img[$i]))
            @php $isVideo = preg_match('/\.(mp4|webm|ogg|mov)(\?|$)/i', $img[$i] ?? ''); @endphp
            @if($isVideo)
            <video src="{{ (str_starts_with($img[$i], 'http') || str_starts_with($img[$i], '/')) ? $img[$i] : asset($img[$i]) }}" controls class="preview-media" style="max-width:140px;max-height:90px;"></video>
            @else
            <img src="{{ (str_starts_with($img[$i], 'http') || str_starts_with($img[$i], '/')) ? $img[$i] : asset($img[$i]) }}" alt="Preview" class="preview-media">
            @endif
            @endif
        </div>
        <button type="button" class="upload-btn" data-slot="img-{{ $i }}">Subir imagen o video</button>
        <input type="file" accept="image/*,video/*" class="img-file-input" data-slot="img-{{ $i }}" style="display:none">
        <label class="alt-label">Alt (descripción) {{ $i + 1 }}</label>
        <input type="text" name="alt[]" value="{{ $alt[$i] ?? '' }}" placeholder="Descripción de la imagen o video">
    </div>
    @endfor

    <h3>Imágenes extras (galería)</h3>
    @php
        $imgExtras = is_array($noticia->imgExtras ?? null) ? $noticia->imgExtras : [];
        $altExtras = is_array($noticia->altExtras ?? null) ? $noticia->altExtras : [];
        while (count($imgExtras) < 3) { $imgExtras[] = ''; $altExtras[] = ''; }
        $imgExtras = array_slice($imgExtras, 0, 3);
        $altExtras = array_slice($altExtras, 0, 3);
    @endphp
    @for($i = 0; $i < 3; $i++)
    <div class="img-slot">
        <label>Imagen o video extra {{ $i + 1 }}</label>
        <input type="text" name="imgExtras[]" value="{{ $imgExtras[$i] ?? '' }}" class="img-url-input" aria-hidden="true">
        <div class="img-preview-wrap" data-preview="extras-{{ $i }}">
            @if(!empty($imgExtras[$i]))
            @php $isVideoExtra = preg_match('/\.(mp4|webm|ogg|mov)(\?|$)/i', $imgExtras[$i] ?? ''); @endphp
            @if($isVideoExtra)
            <video src="{{ (str_starts_with($imgExtras[$i], 'http') || str_starts_with($imgExtras[$i], '/')) ? $imgExtras[$i] : asset($imgExtras[$i]) }}" controls class="preview-media" style="max-width:140px;max-height:90px;"></video>
            @else
            <img src="{{ (str_starts_with($imgExtras[$i], 'http') || str_starts_with($imgExtras[$i], '/')) ? $imgExtras[$i] : asset($imgExtras[$i]) }}" alt="Preview" class="preview-media">
            @endif
            @endif
        </div>
        <button type="button" class="upload-btn" data-slot="extras-{{ $i }}">Subir imagen o video</button>
        <input type="file" accept="image/*,video/*" class="img-file-input" data-slot="extras-{{ $i }}" style="display:none">
        <label class="alt-label">Alt extra {{ $i + 1 }}</label>
        <input type="text" name="altExtras[]" value="{{ $altExtras[$i] ?? '' }}">
    </div>
    @endfor

    <div class="form-actions">
        <a href="{{ route('admin.index') }}" class="cancel-button">Cancelar</a>
        <button type="submit" class="save-button">Guardar</button>
    </div>
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/trix@2.0.8/dist/trix.umd.min.js"></script>
<script>
document.querySelectorAll('.upload-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var slot = this.getAttribute('data-slot');
        var fileInput = document.querySelector('.img-file-input[data-slot="' + slot + '"]');
        if (fileInput) fileInput.click();
    });
});
function isVideoUrl(url) {
    return /\.(mp4|webm|ogg|mov)(\?|$)/i.test(url || '');
}
document.querySelectorAll('.img-file-input').forEach(function(input) {
    input.addEventListener('change', function() {
        var file = this.files[0];
        if (!file) return;
        var btn = document.querySelector('.upload-btn[data-slot="' + this.getAttribute('data-slot') + '"]');
        var urlInput = this.closest('.img-slot').querySelector('input.img-url-input');
        btn.disabled = true;
        btn.textContent = 'Subiendo...';
        var formData = new FormData();
        formData.append('file', file);
        formData.append('type', (file.type && file.type.indexOf('video/') === 0) ? 'video' : 'img');
        fetch('{{ route("upload.store") }}', {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        }).then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.url) {
                urlInput.value = data.url;
                var wrap = urlInput.closest('.img-slot').querySelector('.img-preview-wrap');
                if (wrap) {
                    wrap.innerHTML = '';
                    var fullUrl = data.url.startsWith('http') || data.url.startsWith('/') ? data.url : (window.location.origin + (data.url.startsWith('/') ? '' : '/') + data.url);
                    if (isVideoUrl(data.url)) {
                        var v = document.createElement('video');
                        v.src = fullUrl;
                        v.controls = true;
                        v.className = 'preview-media';
                        v.style.maxWidth = '140px'; v.style.maxHeight = '90px';
                        wrap.appendChild(v);
                    } else {
                        var im = document.createElement('img');
                        im.src = fullUrl;
                        im.alt = 'Preview';
                        im.className = 'preview-media';
                        wrap.appendChild(im);
                    }
                }
            } else if (data.error) alert(data.error);
        }).catch(function() { alert('Error al subir'); })
        .finally(function() {
            btn.disabled = false;
            btn.textContent = 'Subir imagen o video';
            input.value = '';
        });
    });
});
</script>
@endpush
@endsection
