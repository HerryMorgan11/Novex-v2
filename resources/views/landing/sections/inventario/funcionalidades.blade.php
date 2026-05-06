@push('styles')
@vite(['resources/css/landing/sections/inventario/funcionalidades.css'])
@endpush

<section class="inv-func-section">
    <div class="inv-func-inner">
        <div class="inv-func-header">
            <p class="inv-func-pretitle">Funcionalidades del Módulo</p>
            <h2 class="inv-func-title">Del proveedor al cliente,<br>todo bajo control</h2>
            <p class="inv-func-subtitle">
                Novex Inventario cubre el ciclo completo de la mercancía: desde que el proveedor
                envía el transporte hasta que el repartidor confirma la entrega al destinatario final.
            </p>
        </div>

        <div class="inv-func-grid">
            <!-- Card 1 -->
            <div class="inv-func-card">
                <div class="inv-func-icon">
                    <iconify-icon icon="mdi:truck-delivery-outline"></iconify-icon>
                </div>
                <h3 class="inv-func-card-title">Recepción de Mercancía</h3>
                <p class="inv-func-card-desc">
                    El proveedor o sistema externo envía el transporte a través de un endpoint.
                    Novex lo recibe, registra los artículos y los incorpora automáticamente al stock
                    del almacén que corresponda.
                </p>
                <ul class="inv-func-list">
                    <li>Integración vía API con proveedores</li>
                    <li>Registro automático de artículos al llegar</li>
                    <li>Asignación a almacén y ubicación</li>
                    <li>Historial de transportes recibidos</li>
                </ul>
            </div>

            <!-- Card 2 -->
            <div class="inv-func-card">
                <div class="inv-func-icon">
                    <iconify-icon icon="mdi:warehouse"></iconify-icon>
                </div>
                <h3 class="inv-func-card-title">Gestión de Stock</h3>
                <p class="inv-func-card-desc">
                    Una vez en stock, puedes ver en tiempo real cuántas unidades hay de cada
                    artículo, en qué almacén están y cuál es su estado. El sistema avisa cuando
                    los niveles bajan del mínimo configurado.
                </p>
                <ul class="inv-func-list">
                    <li>Vista en tiempo real de existencias</li>
                    <li>Múltiples almacenes y ubicaciones</li>
                    <li>Alertas de stock mínimo</li>
                    <li>Conteos físicos y ajustes</li>
                </ul>
            </div>

            <!-- Card 3 -->
            <div class="inv-func-card">
                <div class="inv-func-icon">
                    <iconify-icon icon="mdi:swap-horizontal-bold"></iconify-icon>
                </div>
                <h3 class="inv-func-card-title">Movimientos Internos</h3>
                <p class="inv-func-card-desc">
                    Mueve mercancía desde el stock a producción o a reparto según la necesidad.
                    Cada movimiento queda registrado con fecha, origen, destino y responsable,
                    manteniendo la trazabilidad completa en todo momento.
                </p>
                <ul class="inv-func-list">
                    <li>Transferencia a producción o reparto</li>
                    <li>Registro de movimientos con trazabilidad</li>
                    <li>Control de lotes por movimiento</li>
                    <li>Historial completo por artículo</li>
                </ul>
            </div>

            <!-- Card 4 -->
            <div class="inv-func-card">
                <div class="inv-func-icon">
                    <iconify-icon icon="mdi:map-marker-check-outline"></iconify-icon>
                </div>
                <h3 class="inv-func-card-title">Expediciones y Entrega</h3>
                <p class="inv-func-card-desc">
                    Cuando la mercancía se mueve a reparto, se genera una expedición. Al confirmar
                    que el destinatario la ha recibido, el artículo sale definitivamente del stock.
                    Sin confirmación, no hay salida.
                </p>
                <ul class="inv-func-list">
                    <li>Gestión de expediciones por reparto</li>
                    <li>Confirmación de recepción por el destinatario</li>
                    <li>Salida de stock solo al confirmar entrega</li>
                    <li>Seguimiento del estado de cada envío</li>
                </ul>
            </div>
        </div>
    </div>
</section>
