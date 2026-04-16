@extends('dashboard.index')

@section('content')
<div class="space-y-6">
    
    <!-- Encabezado de Sección -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Recepciones de Mercadería</h2>
            <p class="text-sm text-gray-500 mt-1">Monitorea y gestiona la entrada de productos al almacén.</p>
        </div>
        <button 
            onclick="location.href='#'" 
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white text-sm font-medium rounded-xl transition-all shadow-sm hover:shadow-md active:scale-95"
        >
            <iconify-icon icon="lucide:plus" class="text-lg"></iconify-icon>
            Nueva Recepción
        </button>
    </div>

    @if(isset($error) && empty($recepciones))
        <!-- Estado Vacío o Error -->
        <div class="flex flex-col items-center justify-center py-16 px-2 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                <iconify-icon icon="lucide:truck" class="text-3xl"></iconify-icon>
            </div>
            <h3 class="text-gray-900 font-semibold mb-1">No hay recepciones registradas</h3>
            <p class="text-gray-500 text-sm text-center mb-6 max-w-sm">{{ $error }}</p>
        </div>
    @else
        <!-- Grid de Recepciones -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ($recepciones as $recepcion)
                <div class="group relative bg-white border border-gray-200 rounded-2xl p-5 hover:border-gray-300 transition-all duration-300">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <iconify-icon icon="lucide:truck" class="text-xl"></iconify-icon>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-md border {{ $recepcion['estado'] === 'PENDIENTE' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-green-50 text-green-600 border-green-100' }}">
                            {{ $recepcion['estado'] }}
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="font-bold text-gray-900 mb-0.5">{{ $recepcion['recepcion_id'] }}</h3>
                        <p class="text-xs text-gray-500 font-medium truncate">{{ $recepcion['proveedor']['nombre'] }}</p>
                    </div>
                    
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <iconify-icon icon="lucide:calendar" class="text-gray-400"></iconify-icon>
                            <span>Est: {{ \Carbon\Carbon::parse($recepcion['fecha_estimada'])->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <iconify-icon icon="lucide:package" class="text-gray-400"></iconify-icon>
                            <span>{{ $recepcion['total_productos'] }} SKUs • {{ $recepcion['cantidad_total'] }} und.</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <a href="{{ route('inventario.recepcion', $recepcion['recepcion_id']) }}" 
                           class="text-xs font-semibold text-gray-600 hover:text-white hover:bg-blue-600 bg-gray-50 px-4 py-2 rounded-lg transition-all w-full text-center block"
                        >
                            Gestionar Carga
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function verDetallesRecepcion(id) {
        console.log('Gestionando recepción:', id);
        // Aquí podrías abrir un modal o navegar a una ruta de detalle
    }
</script>
@endsection
