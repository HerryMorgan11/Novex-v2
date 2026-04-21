@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css', 'resources/css/dashboard/features/inventario/expediciones.css'])
@endpush

@section('content')
<div class="inv-page-wrapper">

    @include('dashboard.features.inventario.partials.top-nav')

    <div class="inv-page-header">
        <div>
            <h1>Expediciones</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" class="inv-breadcrumb-link">Inventario</a>
                &rsaquo; Expediciones
            </div>
        </div>
        <a href="{{ route('inventario.expediciones.create') }}" class="inv-btn inv-btn-primary">
            <iconify-icon icon="lucide:plus"></iconify-icon>
            Nueva expedición
        </a>
    </div>

    @if(session('success'))
    <div class="inv-alert inv-alert-success"><iconify-icon icon="lucide:check-circle"></iconify-icon> {{ session('success') }}</div>
    @endif

    @if($expediciones->isEmpty())
    <div class="inv-empty">
        <iconify-icon icon="lucide:send"></iconify-icon>
        <p>No hay expediciones registradas.</p>
        <a href="{{ route('inventario.expediciones.create') }}" class="inv-btn inv-btn-primary">Crear primera expedición</a>
    </div>
    @else
    <div class="inv-table-wrapper">
        <table class="inv-table">
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Tipo</th>
                    <th>Destino</th>
                    <th>Vehículo</th>
                    <th>Fecha salida</th>
                    <th>Líneas</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($expediciones as $exp)
                <tr>
                    <td class="mono">{{ $exp->referencia_expedicion }}</td>
                    <td>
                        <span class="badge {{ $exp->tipo === 'reparto' ? 'badge-primary' : 'badge-purple' }}">
                            {{ $exp->tipo }}
                        </span>
                    </td>
                    <td>{{ $exp->destino ?? '—' }}</td>
                    <td class="mono">{{ $exp->vehiculo ?? '—' }}</td>
                    <td class="inv-td-muted">
                        {{ $exp->fecha_salida?->format('d/m/Y H:i') ?? '—' }}
                    </td>
                    <td>{{ $exp->lineas->count() }}</td>
                    <td>
                        @php $color = $exp->estado?->color() ?? 'secondary' @endphp
                        <span class="badge badge-{{ $color }}">{{ $exp->estado?->label() ?? $exp->estado }}</span>
                    </td>
                    <td>
                        <a href="{{ route('inventario.expediciones.show', $exp->id_expedicion) }}" class="inv-btn inv-btn-ghost inv-btn-icon">
                            <iconify-icon icon="lucide:eye" width="14"></iconify-icon>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="inv-pagination">{{ $expediciones->links() }}</div>
    @endif

</div>
@endsection
