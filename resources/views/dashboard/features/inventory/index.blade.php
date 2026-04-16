@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventory.css'])
@endpush

@section('content')
<div class="inventory-container">
    <div class="inventory-header">
        <h1>Inventario de Productos</h1>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('inventario.almacenes.crear') }}" class="btn-action" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: var(--radius); color: var(--fg); text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <iconify-icon icon="lucide:warehouse"></iconify-icon>
                Añadir Almacén
            </a>
            <a href="{{ route('inventario.categorias.crear') }}" class="btn-action" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: var(--radius); color: var(--fg); text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <iconify-icon icon="lucide:tags"></iconify-icon>
                Añadir Categoría
            </a>
            <a href="{{ route('inventario.productos.create') }}" class="btn-add-product">
                <iconify-icon icon="lucide:plus"></iconify-icon>
                Añadir Producto
            </a>
        </div>
    </div>

    @if(session('success'))
    <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <iconify-icon icon="lucide:check-circle"></iconify-icon>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($productos->isEmpty())
    <div style="text-align: center; padding: 50px; color: var(--muted);">
        <iconify-icon icon="lucide:package-open" style="font-size: 4rem; opacity: 0.2; margin-bottom: 20px;"></iconify-icon>
        <p>No hay productos registrados. ¡Comienza añadiendo uno nuevo!</p>
    </div>
    @else
    <div class="inventory-grid">
        @foreach($productos as $producto)
        <div class="product-card">
            <div class="product-header">
                <div>
                    <strong>{{ $producto->nombre }}</strong>
                    <span class="product-sku">{{ $producto->sku }}</span>
                </div>
                <div class="product-stock {{ ($producto->numero_stock ?? 0) > 0 ? 'stock-ok' : 'stock-low' }}">
                    {{ ($producto->numero_stock ?? 0) > 0 ? $producto->numero_stock . ' uds' : 'Sin stock' }}
                </div>
            </div>
            
            <hr class="product-divider">
            
            <div class="product-body">
                <p style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); margin-bottom: 4px;">Precio</p>
                <div class="product-price">{{ number_format($producto->precio_referencia, 2) }}€</div>
                <p style="margin-top: 10px; font-size: 0.85rem; color: var(--muted);">
                    Categoría: {{ $producto->categoria->nombre ?? 'Sin categoría' }}
                </p>
            </div>
            
            <hr class="product-divider">
            
            <div class="product-actions">
                <button class="btn-action btn-edit" title="Editar">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                    Editar
                </button>
                <button class="btn-action btn-delete" title="Eliminar">
                    <iconify-icon icon="lucide:trash-2"></iconify-icon>
                    Eliminar
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection