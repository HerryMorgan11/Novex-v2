@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventory.css'])
@vite(['resources/css/dashboard/features/notes.css'])
@endpush

@section('content')
<div class="inventory-container" style="max-width: 700px; margin: 0 auto;">
    <div class="inventory-header">
        <h1>Nuevo Almacén</h1>
        <a href="{{ route('inventario.index') }}" style="color: var(--muted); text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 5px;">
            <iconify-icon icon="lucide:arrow-left"></iconify-icon>
            Volver
        </a>
    </div>

    <form action="{{ route('inventario.almacenes.store') }}" method="POST" class="inventory-form">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre del Almacén *</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Almacén Norte, Nave A..." required autofocus>
        </div>

        <div class="form-group">
            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Calle, Número, Ciudad...">
        </div>

        <div class="form-group">
            <label for="responsable">Responsable</label>
            <input type="text" name="responsable" id="responsable" class="form-control" placeholder="Nombre de la persona encargada">
        </div>

        <div style="margin-top: 35px; display: flex; gap: 15px;">
            <button type="submit" class="btn-save" style="flex: 1;">
                Crear Almacén
            </button>
        </div>
    </form>
</div>
@endsection
