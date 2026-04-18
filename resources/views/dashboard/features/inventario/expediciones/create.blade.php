@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css'])
@endpush

@section('content')
<div style="padding: 20px 0; max-width:900px;">

    <div class="inv-page-header">
        <div>
            <h1>Nueva Expedición</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" style="color:var(--muted); text-decoration:none;">Inventario</a>
                &rsaquo;
                <a href="{{ route('inventario.expediciones.index') }}" style="color:var(--muted); text-decoration:none;">Expediciones</a>
                &rsaquo; Nueva
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="inv-alert inv-alert-error">
        <iconify-icon icon="lucide:x-circle"></iconify-icon>
        <div>
            @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="inv-alert inv-alert-error"><iconify-icon icon="lucide:x-circle"></iconify-icon> {{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('inventario.expediciones.store') }}">
        @csrf

        {{-- Datos generales --}}
        <div class="inv-form-card" style="margin-bottom:20px;">
            <h3 style="font-size:0.8rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid var(--border);">
                Datos de la expedición
            </h3>
            <div class="inv-form-grid">
                <div class="inv-form-group">
                    <label>Tipo <span style="color:#b91c1c">*</span></label>
                    <select name="tipo" required>
                        <option value="reparto" {{ old('tipo') !== 'produccion' ? 'selected' : '' }}>Reparto</option>
                        <option value="produccion" {{ old('tipo') === 'produccion' ? 'selected' : '' }}>Producción</option>
                    </select>
                </div>
                <div class="inv-form-group">
                    <label>Destino <span style="color:#b91c1c">*</span></label>
                    <input type="text" name="destino" value="{{ old('destino') }}" required placeholder="Ej: Almacén cliente S.A.">
                </div>
                <div class="inv-form-group">
                    <label>Vehículo / Matrícula</label>
                    <input type="text" name="vehiculo" value="{{ old('vehiculo') }}" placeholder="Ej: 1234ABC">
                </div>
                <div class="inv-form-group">
                    <label>Conductor</label>
                    <input type="text" name="conductor" value="{{ old('conductor') }}" placeholder="Ej: Juan García">
                </div>
                <div class="inv-form-group">
                    <label>Fecha de salida</label>
                    <input type="datetime-local" name="fecha_salida" value="{{ old('fecha_salida', now()->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="inv-form-group" style="grid-column:1/-1;">
                    <label>Observaciones</label>
                    <textarea name="observaciones" rows="2">{{ old('observaciones') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Selección de lotes --}}
        <div class="inv-form-card" style="margin-bottom:20px;">
            <h3 style="font-size:0.8rem; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid var(--border);">
                Seleccionar lotes a enviar
            </h3>

            @if($lotesDisponibles->isEmpty())
            <div class="inv-alert inv-alert-warning">
                <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
                No hay lotes en estado almacenado disponibles para expedición.
            </div>
            @else
            <div class="inv-search" style="max-width:100%; margin-bottom:14px;">
                <iconify-icon icon="lucide:search"></iconify-icon>
                <input type="text" id="lote-search" placeholder="Buscar lote o producto..." oninput="filtrarLotes(this.value)">
            </div>

            <div class="lote-selector-grid" id="lotes-container">
                @foreach($lotesDisponibles as $lote)
                <label class="lote-selector-item" data-nombre="{{ strtolower($lote->producto?->nombre ?? '') }}" data-lote="{{ strtolower($lote->numero_lote) }}">
                    <input type="checkbox" name="lineas[{{ $loop->index }}][id_lote]" value="{{ $lote->id_lote }}"
                        onchange="toggleCantidad(this)"
                        {{ collect(old('lineas', []))->where('id_lote', $lote->id_lote)->count() ? 'checked' : '' }}>
                    <div class="lote-info">
                        <div style="font-weight:500; font-size:0.875rem;">{{ $lote->producto?->nombre ?? '—' }}</div>
                        <div class="lote-ref">{{ $lote->numero_lote }}</div>
                        <div style="font-size:0.75rem; color:var(--muted); margin-top:2px;">
                            Disponible: <strong>{{ number_format($lote->cantidadDisponible(), 0) }}</strong>
                            {{ $lote->producto?->unidadMedida?->abreviatura ?? '' }}
                            @if($lote->ubicacion)
                            · {{ $lote->ubicacion->codigoCompleto() }}
                            @endif
                        </div>
                    </div>
                    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                        <input type="number" name="lineas[{{ $loop->index }}][cantidad]"
                            class="lote-qty-input"
                            min="0.001"
                            max="{{ $lote->cantidadDisponible() }}"
                            step="0.001"
                            placeholder="Cant."
                            style="display:none;"
                            {{ collect(old('lineas', []))->where('id_lote', $lote->id_lote)->count() ? '' : 'disabled' }}>
                        <input type="hidden" name="lineas[{{ $loop->index }}][unidad]"
                            value="{{ $lote->producto?->unidadMedida?->abreviatura ?? '' }}">
                    </div>
                </label>
                @endforeach
            </div>
            @endif
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;">
            <a href="{{ route('inventario.expediciones.index') }}" class="inv-btn inv-btn-outline">Cancelar</a>
            <button type="submit" class="inv-btn inv-btn-primary">
                <iconify-icon icon="lucide:send"></iconify-icon>
                Crear expedición
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
function toggleCantidad(checkbox) {
    const row = checkbox.closest('.lote-selector-item');
    const input = row.querySelector('.lote-qty-input');
    if (checkbox.checked) {
        input.style.display = 'block';
        input.disabled = false;
        input.required = true;
    } else {
        input.style.display = 'none';
        input.disabled = true;
        input.required = false;
        input.value = '';
    }
}

function filtrarLotes(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.lote-selector-item').forEach(item => {
        const nombre = item.dataset.nombre;
        const lote   = item.dataset.lote;
        item.style.display = (!q || nombre.includes(q) || lote.includes(q)) ? '' : 'none';
    });
}
</script>
@endpush
@endsection
