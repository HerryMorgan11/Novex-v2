@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventory.css'])
@vite(['resources/css/dashboard/features/notes.css']) {{-- To reuse form styles --}}
@endpush

@section('content')
<div class="inventory-container" style="max-width: 900px; margin: 0 auto;">
    <div class="inventory-header">
        <h1>Añadir Nuevo Producto</h1>
        <a href="{{ route('inventario.index') }}" style="color: var(--muted); text-decoration: none; font-weight: 500; display: flex; align-items: center; gap: 5px;">
            <iconify-icon icon="lucide:arrow-left"></iconify-icon>
            Volver al Inventario
        </a>
    </div>

    <form action="{{ route('inventario.productos.store') }}" method="POST" class="inventory-form">
        @csrf
        <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
            
            <div class="form-group">
                <label for="nombre">Nombre del Producto *</label>
                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Laptop Pro 15" required autofocus>
            </div>

            <div class="form-group">
                <label for="codigo">Código / SKU *</label>
                <input type="text" name="codigo" id="codigo" class="form-control" placeholder="Ej: LPT-001" required>
            </div>

            <div class="form-group">
                <label for="categoria_id">Categoría *</label>
                <select name="categoria_id" id="categoria_id" class="form-control" required>
                    <option value="">Seleccionar Categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="precio_venta">Precio de Venta (€) *</label>
                <input type="number" step="0.01" name="precio_venta" id="precio_venta" class="form-control" placeholder="0.00" required>
            </div>

            <div class="form-group">
                <label for="stock">Stock Inicial *</label>
                <input type="number" name="stock" id="stock" class="form-control" placeholder="0" required>
            </div>

            <div class="form-group">
                <label for="almacen_id">Almacén (Ubicación)</label>
                <select name="almacen_id" id="almacen_id" class="form-control">
                    <option value="">Seleccionar Almacén</option>
                    @foreach($naves as $nave)
                        <option value="{{ $nave->id_almacen }}">{{ $nave->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" style="min-height: 100px;" placeholder="Detalles del producto..."></textarea>
            </div>

        </div>

        <div style="margin-top: 40px; display: flex; gap: 15px;">
            <button type="submit" class="btn-save" style="flex: 1;">
                Guardar Producto
            </button>
            <a href="{{ route('inventario.index') }}" class="btn-action" style="padding: 14px 25px; border: 1px solid var(--border); border-radius: var(--radius); color: var(--fg); text-decoration: none; text-align: center;">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
