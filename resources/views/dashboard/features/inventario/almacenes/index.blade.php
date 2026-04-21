@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css'])
@endpush

@section('content')
<div style="padding: 20px 0;">

    <div class="inv-page-header">
        <div>
            <h1>Almacenes</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" style="color:var(--muted); text-decoration:none;">Inventario</a>
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
    <div class="inv-detail-card" style="margin-bottom:20px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
            <div>
                <div style="font-size:1.1rem; font-weight:700;">
                    <iconify-icon icon="lucide:warehouse"></iconify-icon>
                    {{ $almacen->nombre }}
                </div>
                @if($almacen->direccion)
                <div style="font-size:0.82rem; color:var(--muted); margin-top:3px;">
                    <iconify-icon icon="lucide:map-pin" width="12"></iconify-icon>
                    {{ $almacen->direccion }}
                </div>
                @endif
            </div>
            <span class="badge badge-success">Activo</span>
        </div>

        {{-- Zonas --}}
        @if($almacen->zonas->isNotEmpty())
        <div style="margin-top:12px;">
            @foreach($almacen->zonas as $zona)
            <div style="margin-bottom:16px;">
                <div style="font-size:0.82rem; font-weight:600; color:var(--muted); display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                    <iconify-icon icon="lucide:grid-2x2" width="13"></iconify-icon>
                    Zona: {{ $zona->nombre }}
                </div>

                @if($zona->estanterias->isNotEmpty())
                <div style="padding-left:16px; display:flex; flex-wrap:wrap; gap:8px;">
                    @foreach($zona->estanterias as $est)
                    <div style="background:var(--surface-2); border:1px solid var(--border); border-radius:8px; padding:10px 14px; min-width:140px;">
                        <div style="font-size:0.8rem; font-weight:600; margin-bottom:6px;">
                            <iconify-icon icon="lucide:layers" width="12"></iconify-icon>
                            Estantería {{ $est->codigo }}
                        </div>
                        @forelse($est->ubicaciones as $ub)
                        <div style="font-size:0.72rem; font-family:monospace; color:var(--muted); padding:2px 0;">
                            <iconify-icon icon="lucide:map-pin" width="10"></iconify-icon>
                            {{ $ub->codigoCompleto() }}
                        </div>
                        @empty
                        <div style="font-size:0.72rem; color:var(--muted); padding:2px 0;">Sin ubicaciones</div>
                        @endforelse
                        <button
                            type="button"
                            onclick="abrirModalUbicacion({{ \Illuminate\Support\Js::from(route('inventario.almacenes.ubicaciones.store', $almacen->id_almacen)) }}, {{ $est->id_estanteria }})"
                            class="inv-btn inv-btn-ghost"
                            style="font-size:0.72rem; padding:4px 8px; margin-top:8px;">
                            <iconify-icon icon="lucide:plus" width="12"></iconify-icon>
                            Ubicación
                        </button>
                    </div>
                    @endforeach
                </div>
                @else
                <p style="font-size:0.78rem; color:var(--muted-2); padding-left:16px;">Sin estanterías</p>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <p style="font-size:0.82rem; color:var(--muted);">Sin zonas configuradas.</p>
        @endif

        {{-- Acciones rápidas --}}
        <div style="display:flex; gap:10px; margin-top:16px; padding-top:14px; border-top:1px solid var(--border); flex-wrap:wrap;">
            <button onclick="abrirModalZona({{ \Illuminate\Support\Js::from(route('inventario.almacenes.zonas.store', $almacen->id_almacen)) }})" class="inv-btn inv-btn-outline" style="font-size:0.8rem; padding:6px 14px;">
                <iconify-icon icon="lucide:plus" width="13"></iconify-icon>
                Añadir zona
            </button>
            <button onclick="abrirModalEstanteria({{ \Illuminate\Support\Js::from(route('inventario.almacenes.estanterias.store', $almacen->id_almacen)) }}, {{ \Illuminate\Support\Js::from($almacen->zonas->map->only(['id_zona', 'nombre'])->values()) }})" class="inv-btn inv-btn-outline" style="font-size:0.8rem; padding:6px 14px;">
                <iconify-icon icon="lucide:layers" width="13"></iconify-icon>
                Añadir estantería
            </button>
        </div>
    </div>
    @endforeach

    @endif

</div>

{{-- Modal zona --}}
<div id="modal-zona" hidden style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:28px; width:400px; max-width:95vw;">
        <h3 style="font-size:1rem; font-weight:600; margin-bottom:16px;">Añadir zona</h3>
        <form id="form-zona" method="POST" action="">
            @csrf
            <div class="inv-form-group" style="margin-bottom:16px;">
                <label>Nombre de la zona *</label>
                <input type="text" name="nombre" required placeholder="Ej: Zona A">
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="cerrarModales()" class="inv-btn inv-btn-outline">Cancelar</button>
                <button type="submit" class="inv-btn inv-btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal estantería --}}
<div id="modal-estanteria" hidden style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:28px; width:400px; max-width:95vw;">
        <h3 style="font-size:1rem; font-weight:600; margin-bottom:16px;">Añadir estantería</h3>
        <form id="form-estanteria" method="POST" action="">
            @csrf
            <div class="inv-form-group" style="margin-bottom:16px;">
                <label>Zona *</label>
                <select id="est-zona-select" name="id_zona" class="inv-select" required></select>
            </div>
            <div class="inv-form-group" style="margin-bottom:16px;">
                <label>Código de estantería *</label>
                <input type="text" name="codigo" required placeholder="Ej: E01">
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="cerrarModales()" class="inv-btn inv-btn-outline">Cancelar</button>
                <button type="submit" class="inv-btn inv-btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal ubicación --}}
<div id="modal-ubicacion-almacen" hidden style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:28px; width:460px; max-width:95vw;">
        <h3 style="font-size:1rem; font-weight:600; margin-bottom:16px;">Añadir ubicación</h3>
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
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:18px;">
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
