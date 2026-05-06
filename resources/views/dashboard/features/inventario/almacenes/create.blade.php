@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario/general-inventario.css', 'resources/css/dashboard/features/inventario/almacenes.css'])
@endpush

@section('content')
<div class="inv-page-wrapper-narrow">

    @include('dashboard.features.inventario.partials.top-nav')

    <div class="inv-page-header">
        <div>
            <h1>Nuevo Almacén</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" class="inv-breadcrumb-link">Inventario</a>
                &rsaquo;
                <a href="{{ route('inventario.almacenes.index') }}" class="inv-breadcrumb-link">Almacenes</a>
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
                <div class="inv-form-group inv-form-full-width">
                    <label>Nombre del almacén *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="Ej: Almacén Central">
                </div>
                <div class="inv-form-group inv-form-full-width">
                    <label>Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Ej: Calle Industrial 45, Nave 2">
                </div>
                <div class="inv-form-group">
                    <label>Responsable</label>
                    <input type="text" name="responsable" value="{{ old('responsable') }}" placeholder="Ej: Carlos Pérez">
                </div>
            </div>
            <div class="inv-form-actions">
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
