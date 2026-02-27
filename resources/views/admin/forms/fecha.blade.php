@extends('layouts.admin-form')

@section('title', ($fecha ? 'Editar fecha' : 'Nueva fecha') . ' | Carpir Admin')
@section('modal_title', $fecha ? 'Editar fecha' : 'Nueva fecha')

@push('styles')
<style>
    .admin-form-body .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 600px) { .admin-form-body .form-row { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<form method="POST" action="{{ $fecha ? route('admin.fechas.update', $fecha->id) : route('admin.fechas.store') }}">
    @csrf
    @if($fecha) @method('PUT') @endif

    <div class="form-row">
        <div class="form-group">
            <label for="fecha">Fecha *</label>
            <input id="fecha" type="date" name="fecha" value="{{ old('fecha', $fecha->fecha ?? '') }}" required>
        </div>
        <div class="form-group">
            <label for="horario">Horario</label>
            <input id="horario" type="text" name="horario" value="{{ old('horario', $fecha->horario ?? '') }}" placeholder="Ej: 21:00">
        </div>
    </div>

    <div class="form-group">
        <label for="locacion">Locación</label>
        <input id="locacion" type="text" name="locacion" value="{{ old('locacion', $fecha->locacion ?? '') }}" placeholder="Ej: Teatro XYZ">
    </div>

    <div class="form-group">
        <label for="direccion">Dirección</label>
        <input id="direccion" type="text" name="direccion" value="{{ old('direccion', $fecha->direccion ?? '') }}" placeholder="Calle, número, ciudad">
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="costo">Costo</label>
            <input id="costo" type="text" name="costo" value="{{ old('costo', $fecha->costo ?? '') }}" placeholder="Ej: Entrada libre / $500">
        </div>
        <div class="form-group">
            <label for="link_entradas">Link a entradas</label>
            <input id="link_entradas" type="url" name="link_entradas" value="{{ old('link_entradas', $fecha->link_entradas ?? '') }}" placeholder="https://...">
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.index') }}" class="cancel-button">Cancelar</a>
        <button type="submit" class="save-button">Guardar</button>
    </div>
</form>
@endsection
