@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css', 'resources/css/dashboard/features/inventario/transportes.css'])
@endpush

@section('content')
<div class="inv-page-wrapper">

    @include('dashboard.features.inventario.partials.top-nav')

    <div class="inv-page-header">
        <div>
            <h1>Transportes</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" class="inv-breadcrumb-link">Inventario</a>
                &rsaquo; Transportes
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="inv-alert inv-alert-success"><iconify-icon icon="lucide:check-circle"></iconify-icon> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="inv-alert inv-alert-error"><iconify-icon icon="lucide:x-circle"></iconify-icon> {{ session('error') }}</div>
    @endif

    @if($transportes->isEmpty())
    <div class="inv-empty">
        <iconify-icon icon="lucide:truck"></iconify-icon>
        <p>No hay transportes registrados aún.</p>
        <p class="inv-hint-text">Los transportes se crean automáticamente cuando un sistema externo envía un aviso por API.</p>
    </div>
    @else
    <div class="transport-grid">
        @foreach($transportes as $transporte)
        <a href="{{ route('inventario.transportes.show', $transporte->id_recepcion) }}" class="transport-card">
            <div class="transport-card-header">
                <span class="transport-card-ref">{{ $transporte->codigo_recepcion }}</span>
                @php $color = $transporte->estado?->color() ?? 'secondary' @endphp
                <span class="badge badge-{{ $color }}">{{ $transporte->estado?->label() ?? $transporte->estado }}</span>
            </div>

            <div class="inv-transport-name">
                {{ $transporte->transportista ?? $transporte->proveedor?->nombre ?? 'Sin transportista' }}
            </div>

            <div class="transport-card-meta">
                @if($transporte->origen || $transporte->destino)
                <span>
                    <iconify-icon icon="lucide:map-pin" width="13"></iconify-icon>
                    {{ $transporte->origen ?? '—' }} → {{ $transporte->destino ?? '—' }}
                </span>
                @endif
                @if($transporte->patente)
                <span>
                    <iconify-icon icon="lucide:car" width="13"></iconify-icon>
                    {{ $transporte->patente }}
                </span>
                @endif
                @if($transporte->fecha_estimada)
                <span>
                    <iconify-icon icon="lucide:calendar" width="13"></iconify-icon>
                    Previsto: {{ $transporte->fecha_estimada->format('d/m/Y H:i') }}
                </span>
                @endif
            </div>

            <div class="transport-card-footer">
                <span>
                    <iconify-icon icon="lucide:package" width="13"></iconify-icon>
                    {{ $transporte->lineas->count() }} {{ Str::plural('línea', $transporte->lineas->count()) }}
                </span>
                <span>{{ $transporte->created_at->diffForHumans() }}</span>
            </div>
        </a>
        @endforeach
    </div>

    <div class="inv-pagination">
        {{ $transportes->links() }}
    </div>
    @endif

</div>
@endsection
