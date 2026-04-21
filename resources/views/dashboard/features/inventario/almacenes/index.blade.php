@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css', 'resources/css/dashboard/features/inventario/almacenes.css'])
@endpush

@section('content')
<div class="inv-page-wrapper">

    @include('dashboard.features.inventario.partials.top-nav')

    <div class="inv-page-header">
        <div>
            <h1>Almacenes</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" class="inv-breadcrumb-link">Inventario</a>
                &rsaquo; Almacenes
            </div>
        </div>
        <a href="{{ route('inventario.almacenes.create') }}" class="inv-btn inv-btn-primary">
            <iconify-icon icon="lucide:plus"></iconify-icon>
            Nuevo almacén
        </a>
    </div>

    @if(session('success'))
    <div class="inv-alert inv-alert-success"><iconify-icon icon="lucide:check-circle"></iconify-icon> {{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="inv-alert inv-alert-error">
        <iconify-icon icon="lucide:x-circle"></iconify-icon>
        <div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
    </div>
    @endif

    @if($almacenes->isEmpty())
    <div class="inv-empty">
        <iconify-icon icon="lucide:warehouse"></iconify-icon>
        <p>No hay almacenes configurados.</p>
        <a href="{{ route('inventario.almacenes.create') }}" class="inv-btn inv-btn-primary">Crear primer almacén</a>
    </div>
    @else

    @foreach($almacenes as $almacen)
    <div class="inv-detail-card">
        <div class="inv-card-top-row">
            <div>
                <div class="inv-almacen-name">
                    <iconify-icon icon="lucide:warehouse"></iconify-icon>
                    {{ $almacen->nombre }}
                </div>
                @if($almacen->direccion)
                <div class="inv-almacen-address">
                    <iconify-icon icon="lucide:map-pin" width="12"></iconify-icon>
                    {{ $almacen->direccion }}
                </div>
                @endif
            </div>
            <span class="badge badge-success">Activo</span>
        </div>

        {{-- Zonas --}}
        @if($almacen->zonas->isNotEmpty())
        <div class="inv-zones-wrapper">
            @foreach($almacen->zonas as $zona)
            <div class="inv-zone-item">
                <div class="inv-zone-header">
                    <iconify-icon icon="lucide:grid-2x2" width="13"></iconify-icon>
                    Zona: {{ $zona->nombre }}
                </div>

                @if($zona->estanterias->isNotEmpty())
                <div class="inv-estanterias-list">
                    @foreach($zona->estanterias as $est)
                    <div class="inv-estanteria-card">
                        <div class="inv-estanteria-name">
                            <iconify-icon icon="lucide:layers" width="12"></iconify-icon>
                            Estantería {{ $est->codigo }}
                        </div>
                        @forelse($est->ubicaciones as $ub)
                        <div class="inv-ubicacion-item">
                            <iconify-icon icon="lucide:map-pin" width="10"></iconify-icon>
                            {{ $ub->codigoCompleto() }}
                        </div>
                        @empty
                        <div class="inv-ubicacion-empty">Sin ubicaciones</div>
                        @endforelse
                        <button
                            type="button"
                            onclick="abrirModalUbicacion({{ \Illuminate\Support\Js::from(route('inventario.almacenes.ubicaciones.store', $almacen->id_almacen)) }}, {{ $est->id_estanteria }})"
                            class="inv-btn inv-btn-ghost inv-btn-xs-mt">
                            <iconify-icon icon="lucide:plus" width="12"></iconify-icon>
                            Ubicación
                        </button>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="inv-zone-no-shelves">Sin estanterías</p>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <p class="inv-card-muted-text">Sin zonas configuradas.</p>
        @endif

        {{-- Acciones rápidas --}}
        <div class="inv-card-actions">
            <button onclick="abrirModalZona({{ \Illuminate\Support\Js::from(route('inventario.almacenes.zonas.store', $almacen->id_almacen)) }})" class="inv-btn inv-btn-outline inv-btn-sm-action">
                <iconify-icon icon="lucide:plus" width="13"></iconify-icon>
                Añadir zona
            </button>
            <button onclick="abrirModalEstanteria({{ \Illuminate\Support\Js::from(route('inventario.almacenes.estanterias.store', $almacen->id_almacen)) }}, {{ \Illuminate\Support\Js::from($almacen->zonas->map->only(['id_zona', 'nombre'])->values()) }})" class="inv-btn inv-btn-outline inv-btn-sm-action">
                <iconify-icon icon="lucide:layers" width="13"></iconify-icon>
                Añadir estantería
            </button>
        </div>
    </div>
    @endforeach

    @endif

</div>

{{-- Modal zona --}}
<div id="modal-zona" hidden class="inv-modal-overlay">
    <div class="inv-modal-dialog">
        <h3 class="inv-modal-title">Añadir zona</h3>
        <form id="form-zona" method="POST" action="">
            @csrf
            <div class="inv-form-group inv-form-mb">
                <label>Nombre de la zona *</label>
                <input type="text" name="nombre" required placeholder="Ej: Zona A">
            </div>
            <div class="inv-modal-footer">
                <button type="button" onclick="cerrarModales()" class="inv-btn inv-btn-outline">Cancelar</button>
                <button type="submit" class="inv-btn inv-btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal estantería --}}
<div id="modal-estanteria" hidden class="inv-modal-overlay">
    <div class="inv-modal-dialog">
        <h3 class="inv-modal-title">Añadir estantería</h3>
        <form id="form-estanteria" method="POST" action="">
            @csrf
            <div class="inv-form-group inv-form-mb">
                <label>Zona *</label>
                <select id="est-zona-select" name="id_zona" class="inv-select" required></select>
            </div>
            <div class="inv-form-group inv-form-mb">
                <label>Código de estantería *</label>
                <input type="text" name="codigo" required placeholder="Ej: E01">
            </div>
            <div class="inv-modal-footer">
                <button type="button" onclick="cerrarModales()" class="inv-btn inv-btn-outline">Cancelar</button>
                <button type="submit" class="inv-btn inv-btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal ubicación --}}
<div id="modal-ubicacion-almacen" hidden class="inv-modal-overlay">
    <div class="inv-modal-dialog-md">
        <h3 class="inv-modal-title">Añadir ubicación</h3>
        <form id="form-ubicacion-almacen" method="POST" action="">
            @csrf
            <input id="ubicacion-estanteria-id" type="hidden" name="id_estanteria" value="">
            <div class="inv-form-grid">
                <div class="inv-form-group">
                    <label>Pasillo</label>
                    <input type="text" name="pasillo" placeholder="Ej: P1">
                </div>
                <div class="inv-form-group">
                    <label>Nivel</label>
                    <input type="text" name="nivel" placeholder="Ej: N2">
                </div>
                <div class="inv-form-group">
                    <label>Posición</label>
                    <input type="text" name="posicion" placeholder="Ej: 03">
                </div>
                <div class="inv-form-group">
                    <label>Capacidad</label>
                    <input type="number" name="capacidad" min="1" placeholder="100">
                </div>
            </div>
            <div class="inv-modal-footer inv-mt-18">
                <button type="button" onclick="cerrarModales()" class="inv-btn inv-btn-outline">Cancelar</button>
                <button type="submit" class="inv-btn inv-btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function abrirModalZona(actionUrl) {
    document.getElementById('form-zona').action = actionUrl;
    mostrar('modal-zona');
}

function abrirModalEstanteria(actionUrl, zonas) {
    if (!zonas.length) {
        alert('Primero crea una zona en este almacén.');
        return;
    }
    const zonaOptions = zonas.map(z => `<option value="${z.id_zona}">${z.nombre}</option>`).join('');
    document.getElementById('est-zona-select').innerHTML = zonaOptions;
    document.getElementById('form-estanteria').action = actionUrl;
    mostrar('modal-estanteria');
}

function abrirModalUbicacion(actionUrl, estanteriaId) {
    document.getElementById('form-ubicacion-almacen').action = actionUrl;
    document.getElementById('ubicacion-estanteria-id').value = estanteriaId;
    mostrar('modal-ubicacion-almacen');
}

function mostrar(id) {
    const m = document.getElementById(id);
    m.removeAttribute('hidden');
    m.style.display = 'flex';
}

function cerrarModales() {
    ['modal-zona', 'modal-estanteria', 'modal-ubicacion-almacen'].forEach(id => {
        const m = document.getElementById(id);
        m.setAttribute('hidden', '');
        m.style.display = 'none';
    });
}
</script>
@endpush
@endsection
