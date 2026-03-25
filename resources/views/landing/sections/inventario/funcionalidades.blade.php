<style>
    {!! file_get_contents(resource_path('css/landing/sections/inventario/funcionalidades.css')) !!}
</style>

<section class="inv-func-section">
    <div class="inv-func-inner">
        <div class="inv-func-header">
            <p class="inv-func-pretitle">Funcionalidades del Módulo</p>
            <h2 class="inv-func-title">Todo para dominar tu<br>cadena de suministro</h2>
            <p class="inv-func-subtitle">
                Desde el ingreso de mercancía hasta la expedición, Novex Inventario
                te da control total sobre cada movimiento de producto en tu operación.
            </p>
        </div>

        <div class="inv-func-grid">
            <!-- Card 1 -->
            <div class="inv-func-card">
                <div class="inv-func-icon">
                    <iconify-icon icon="mdi:package-variant-closed"></iconify-icon>
                </div>
                <h3 class="inv-func-card-title">Control de Stock</h3>
                <p class="inv-func-card-desc">
                    Monitoreo en tiempo real de niveles de existencias, con alertas automáticas
                    de reposición y valoración de inventario por múltiples métodos estándar.
                </p>
                <ul class="inv-func-list">
                    <li>Niveles mínimos y máximos configurables</li>
                    <li>Valoración FIFO, LIFO y Promedio</li>
                    <li>Conteos físicos y ajustes de inventario</li>
                    <li>Historial completo de movimientos</li>
                </ul>
            </div>

            <!-- Card 2 -->
            <div class="inv-func-card">
                <div class="inv-func-icon">
                    <iconify-icon icon="mdi:warehouse"></iconify-icon>
                </div>
                <h3 class="inv-func-card-title">Gestión de Almacenes</h3>
                <p class="inv-func-card-desc">
                    Organiza tus instalaciones en zonas, pasillos y ubicaciones específicas.
                    Optimiza el espacio y acelera los procesos de picking y putaway.
                </p>
                <ul class="inv-func-list">
                    <li>Mapa de almacén con ubicaciones</li>
                    <li>Múltiples almacenes y bodegas</li>
                    <li>Rutas de picking optimizadas</li>
                    <li>Transferencias entre ubicaciones</li>
                </ul>
            </div>

            <!-- Card 3 -->
            <div class="inv-func-card">
                <div class="inv-func-icon">
                    <iconify-icon icon="mdi:barcode-scan"></iconify-icon>
                </div>
                <h3 class="inv-func-card-title">Rastreo de Productos</h3>
                <p class="inv-func-card-desc">
                    Seguimiento preciso por lote, número de serie o fecha de caducidad.
                    Trazabilidad completa desde la recepción hasta la entrega al cliente.
                </p>
                <ul class="inv-func-list">
                    <li>Gestión por lotes y números de serie</li>
                    <li>Control de fechas de caducidad</li>
                    <li>Trazabilidad bidireccional</li>
                    <li>Lectura de códigos QR y barras</li>
                </ul>
            </div>

            <!-- Card 4 -->
            <div class="inv-func-card">
                <div class="inv-func-icon">
                    <iconify-icon icon="mdi:cart-plus"></iconify-icon>
                </div>
                <h3 class="inv-func-card-title">Órdenes de Compra</h3>
                <p class="inv-func-card-desc">
                    Gestiona el ciclo completo de compras: desde la solicitud hasta la
                    recepción, con control de proveedores y seguimiento de entregas.
                </p>
                <ul class="inv-func-list">
                    <li>Solicitudes de compra automatizadas</li>
                    <li>Comparación de proveedores y precios</li>
                    <li>Recepción parcial y total de pedidos</li>
                    <li>Integración con módulo de finanzas</li>
                </ul>
            </div>
        </div>
    </div>
</section>
