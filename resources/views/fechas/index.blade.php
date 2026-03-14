@extends('layouts.app')

@section('title', 'Próximas fechas | Carpir')
@section('meta_description', 'Consultá las próximas fechas de Carpir, lugares, horarios y links para entradas.')
@section('canonical', route('fechas.index'))

@section('content')
<div class="fechas-page">
    <section class="section fechas-section">
        <h1 class="section-title">Próximas fechas</h1>

        @if($fechas->isEmpty())
        <div class="no-fechas-container">
            <h2 class="no-fechas-title">No hay fechas por el momento</h2>
            <div class="calendar-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
        </div>
        @else
        <div class="fechas-table-wrapper">
            <table class="fechas-table">
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
                            <a href="{{ $f->link_entradas }}" target="_blank" rel="noopener noreferrer" class="fechas-link-entradas">Comprar entradas</a>
                            @else
                            —
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </section>
</div>
@endsection
