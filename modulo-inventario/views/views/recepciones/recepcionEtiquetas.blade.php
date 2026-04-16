@extends('dashboard.index')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb / Volver -->
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('inventario.recepciones') }}" class="hover:text-gray-900 transition-colors">Recepciones</a>
        <iconify-icon icon="lucide:chevron-right" class="text-xs"></iconify-icon>
        <span class="text-gray-900 font-medium">{{ $recepcion->codigo_recepcion }}</span>
    </div>

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Gestión de Carga</h2>
            <p class="text-sm text-gray-500">Registra la mercadería recibida para la orden {{ $recepcion->codigo_recepcion }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 text-xs font-bold rounded-full border {{ $recepcion->estado === 'PENDIENTE' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-green-50 text-green-600 border-green-100' }}">
                {{ $recepcion->estado }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna Izquierda: Información General -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <iconify-icon icon="lucide:truck" class="text-blue-500"></iconify-icon>
                    Información Logística
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Transporte</p>
                        <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $recepcion->nombre_camion ?? 'No especificado' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Patente</p>
                        <p class="text-sm font-mono font-bold text-gray-900 mt-0.5">{{ $recepcion->patente ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Proveedor</p>
                        <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $recepcion->proveedor->nombre_empresa ?? $recepcion->proveedor->nombre ?? 'Proveedor Externo' }}</p>
                    </div>
                </div>
            </div>

            @if($recepcion->observaciones)
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                <h4 class="text-xs font-bold text-blue-900 mb-2 flex items-center gap-2">
                    <iconify-icon icon="lucide:info"></iconify-icon>
                    Observaciones
                </h4>
                <p class="text-xs text-blue-700 leading-relaxed">{{ $recepcion->observaciones }}</p>
            </div>
            @endif
        </div>

        <!-- Columna Derecha: Recepción de Productos -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 flex items-center gap-2">
                        <iconify-icon icon="lucide:package-check" class="text-green-500"></iconify-icon>
                        Recepción de Items
                    </h3>
                    <p class="text-xs text-gray-500">{{ $recepcion->productos->count() }} productos esperados</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/30 border-b border-gray-100">
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Producto</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Esperado</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Recibido</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Unidad</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recepcion->productos as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900">{{ $item->producto_nombre_ref }}</span>
                                        <span class="text-[10px] font-mono text-gray-400">{{ $item->producto_codigo_ref }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-semibold text-gray-600">{{ (float)$item->cantidad_esperada }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center">
                                        <input type="number" 
                                               step="0.01" 
                                               value="{{ (float)$item->cantidad_recibida }}" 
                                               class="w-20 h-8 text-center text-sm border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        >
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-medium text-gray-500">{{ $item->unidad }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-gray-50/50 border-t border-gray-100 flex justify-end gap-3">
                    <button class="px-4 py-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition-colors">
                        Guardar Borrador
                    </button>
                    <button class="px-6 py-2 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition-all shadow-sm active:scale-95">
                        Confirmar Recepción
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection