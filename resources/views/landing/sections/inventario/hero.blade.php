@push('styles')
@vite(['resources/css/landing/sections/inventario/hero.css'])
@endpush

<section class="inv-hero-section">
    <div class="inv-hero-inner">
        <!-- Texto izquierdo -->
        <div class="inv-hero-content-col">
            <div class="inv-hero-badge">
                <span class="inv-hero-badge-dot"></span>
                Módulo de Inventario
            </div>

            <h1 class="inv-hero-title">
                Control Total de tu<br>
                <span>Inventario</span> en<br>
                Tiempo Real
            </h1>

            <p class="inv-hero-desc">
                Novex Inventario gestiona todo el ciclo de la mercancía: recibe envíos desde proveedores a través de un endpoint, los almacena en stock y te permite moverlos a producción o reparto cuando los necesites. Cuando el repartidor confirma la entrega, el artículo sale automáticamente del stock.
            </p>

            <div class="inv-hero-actions">
                <a href="{{ route('register') }}" class="inv-btn-primary">Empieza Gratis</a>
                <a href="{{ route('register') }}" class="inv-btn-secondary">Solicitar acceso</a>
            </div>
        </div>

        <!-- Tarjeta visual derecha -->
        <div class="inv-hero-visual">
            <div class="inv-hero-card">
                <!-- Encabezado -->
                <div class="inv-card-title-row">
                    <span class="inv-card-heading">Estado del Inventario</span>
                    <span class="inv-card-badge">EN VIVO</span>
                </div>

                <!-- Lista de stock -->
                <div class="inv-stock-list">
                    <div class="inv-stock-item">
                        <div class="inv-stock-icon"><iconify-icon icon="mdi:package-variant-closed"></iconify-icon></div>
                        <div class="inv-stock-info">
                            <div class="inv-stock-name">Producto A-201</div>
                            <div class="inv-stock-bar-track">
                                <div class="inv-stock-bar-fill"></div>
                            </div>
                        </div>
                        <span class="inv-stock-qty">850 u.</span>
                    </div>
                    <div class="inv-stock-item">
                        <div class="inv-stock-icon"><iconify-icon icon="mdi:cog-outline"></iconify-icon></div>
                        <div class="inv-stock-info">
                            <div class="inv-stock-name">Componente B-047</div>
                            <div class="inv-stock-bar-track">
                                <div class="inv-stock-bar-fill"></div>
                            </div>
                        </div>
                        <span class="inv-stock-qty">210 u.</span>
                    </div>
                    <div class="inv-stock-item">
                        <div class="inv-stock-icon"><iconify-icon icon="mdi:tag-outline"></iconify-icon></div>
                        <div class="inv-stock-info">
                            <div class="inv-stock-name">SKU-C-933</div>
                            <div class="inv-stock-bar-track">
                                <div class="inv-stock-bar-fill"></div>
                            </div>
                        </div>
                        <span class="inv-stock-qty">1,240 u.</span>
                    </div>
                    <div class="inv-stock-item">
                        <div class="inv-stock-icon"><iconify-icon icon="mdi:clipboard-list-outline"></iconify-icon></div>
                        <div class="inv-stock-info">
                            <div class="inv-stock-name">Lote D-Serie 5</div>
                            <div class="inv-stock-bar-track">
                                <div class="inv-stock-bar-fill"></div>
                            </div>
                        </div>
                        <span class="inv-stock-qty">45 u.</span>
                    </div>
                </div>

                <!-- Alerta de stock bajo -->
                <div class="inv-alert-row">
                    <div class="inv-alert-dot"></div>
                    <span>Lote D-Serie 5 por debajo del mínimo — reposición sugerida</span>
                </div>
            </div>
        </div>
    </div>
</section>
