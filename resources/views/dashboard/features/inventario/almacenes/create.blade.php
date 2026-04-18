@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css'])
@endpush

@section('content')
<div style="padding: 20px 0; max-width:600px;">

    <div class="inv-page-header">
        <div>
            <h1>Nuevo Almacén</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" style="color:var(--muted); text-decoration:none;">Inventario</a>
                &rsaquo;
                <a href="{{ route('inventario.almacenes.index') }}" style="color:var(--muted); text-decoration:none;">Almacenes</a>
                &rsaquo; Nuevo
            </div>
        </div>
    </div>

    @if($errors->any())
    <div class="inv-alert inv-alert-error">
        <iconify-icon icon="lucide:x-circle"></iconify-icon>
        <div>@foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach</div>
    </div>
    @endif

    <div class="inv-form-card">
        <form method="POST" action="{{ route('inventario.almacenes.store') }}">
            @csrf
            <div class="inv-form-grid">
                <div class="inv-form-group" style="grid-column:1/-1;">
                    <label>Nombre del almacén *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Almacén Central">
                </div>
                <div class="inv-form-group" style="grid-column:1/-1;">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Ej: Calle Industrial 45, Nave 2">
                </div>
                <div class="inv-form-group">
                    <label>Responsable</label>
                    <input type="text" name="responsable" value="{{ old('responsable') }}" placeholder="Ej: Carlos Pérez">
                </div>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
                <a href="{{ route('inventario.almacenes.index') }}" class="inv-btn inv-btn-outline">Cancelar</a>
                <button type="submit" class="inv-btn inv-btn-primary">
                    <iconify-icon icon="lucide:save"></iconify-icon>
                    Guardar almacén
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
