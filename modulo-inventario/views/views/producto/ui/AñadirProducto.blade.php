{{-- Create Modal (floating overlay) --}}
<div id="modal-crear-producto" class="hidden fixed inset-0 z-50 items-center justify-center m-0 bg-black/40">

    <!-- modal panel -->
    <div role="dialog" aria-modal="true" aria-labelledby="modal-crear-title"
        class="relative bg-white rounded-lg border border-gray-200 p-6 max-w-2xl mx-4 w-full z-10">

        <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
            <h3 id="modal-crear-title" class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                <iconify-icon icon="lucide:package-plus" class="text-gray-400"></iconify-icon>
                Añadir Nuevo Producto
            </h3>
        </div>
        <form method="POST" action="{{ route('inventario.productos.store') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 items-start">
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500">Nombre *</label>
                    <input id="nombre" name="nombre" placeholder="Nombre del producto" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-all text-gray-900 placeholder-gray-400">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500">Código *</label>
                    <input id="codigo" name="codigo" placeholder="PROD-001" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-all text-gray-900 placeholder-gray-400">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500">Categoría ID *</label>
                    
                    <select id="categoria_id" name="categoria_id" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-all text-gray-900 cursor-pointer appearance-none">
                        <option value="">Seleccionar Categoría</option>
                        @foreach ($categorias as $categoria )
                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500">Precio de Venta *</label>
                    <input id="precio_venta" name="precio_venta" type="number" step="0.01" placeholder="0.00" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-all text-gray-900 placeholder-gray-400">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500">Número en Stock *</label>
                    <input id="stock" name="stock" type="number" placeholder="0" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-all text-gray-900 placeholder-gray-400">
                </div>
                <div class="space-y-1.5 md:col-span-2">
                    <label class="text-xs font-medium text-gray-500">Descripción</label>
                    <textarea id="descripcion" name="descripcion" placeholder="Descripción del producto..." class="w-full h-20 px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-all text-gray-900 placeholder-gray-400"></textarea>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500">Almacén</label>

                     <select id="almacen_id" name="almacen" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-all text-gray-900 cursor-pointer appearance-none">
                        <option value="">Seleccionar Almacén</option>
                        @foreach ($naves as $nave )
                        <option value="{{ $nave->id_almacen }}">{{ $nave->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500">Estantería</label>
                    <select id="estanteria_id" name="estanteria" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-all text-gray-900 cursor-pointer appearance-none" disabled>
                        <option value="">-- Primero selecciona un almacén --</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-500">Posición</label>
                    <input id="posicion" name="posicion" placeholder="P-10" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-900 focus:ring-1 focus:ring-gray-900 transition-all text-gray-900 placeholder-gray-400">
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
                <button type="button" onclick="cerrarFormularioCrear()"
                    class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-all shadow-sm">
                    Añadir Producto
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const almacenSelect = document.getElementById('almacen_id');
        const estanteriaSelect = document.getElementById('estanteria_id');

        if (almacenSelect && estanteriaSelect) {
            almacenSelect.addEventListener('change', function() {
                const almacenId = this.value;

                // Resetear select de estantería
                estanteriaSelect.innerHTML = '<option value="">Cargando...</option>';
                estanteriaSelect.disabled = true;

                if (almacenId) {
                    // Petición AJAX para obtener estanterías
                    fetch(`/control/estanterias/${almacenId}`)
                        .then(response => response.json())
                        .then(data => {
                            estanteriaSelect.innerHTML = '<option value="">Seleccionar Estantería</option>';
                            
                            if (data.length > 0) {
                                data.forEach(estanteria => {
                                    const option = document.createElement('option');
                                    option.value = estanteria.codigo; // O estanteria.id si prefieres guardar el ID
                                    option.textContent = estanteria.codigo;
                                    estanteriaSelect.appendChild(option);
                                });
                                estanteriaSelect.disabled = false;
                            } else {
                                estanteriaSelect.innerHTML = '<option value="">No hay estanterías en este almacén</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error cargando estanterías:', error);
                            estanteriaSelect.innerHTML = '<option value="">Error al cargar</option>';
                        });
                } else {
                    estanteriaSelect.innerHTML = '<option value="">-- Primero selecciona un almacén --</option>';
                }
            });
        }
    });
</script>
