@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventory.css'])
@vite(['resources/css/dashboard/features/notes.css'])
@endpush

@section('content')
<div class="inventory-container" style="max-width: 600px; margin: 0 auto;">
    <div class="inventory-header">
        <h1>Nueva Categoría</h1>
        <a href="{{ route('inventario.index') }}" style="color: var(--muted); text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 5px;">
            <iconify-icon icon="lucide:arrow-left"></iconify-icon>
            Volver
        </a>
    </div>

    <form action="{{ route('inventario.categorias.store') }}" method="POST" class="inventory-form">
        @csrf
        <div class="form-group">
            <label for="nombre">Nombre de la Categoría *</label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Electrónica, Muebles..." required autofocus>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 15px;">
            <button type="submit" class="btn-save" style="flex: 1;">
                Crear Categoría
            </button>
        </div>
    </form>
</div>
@endsection
