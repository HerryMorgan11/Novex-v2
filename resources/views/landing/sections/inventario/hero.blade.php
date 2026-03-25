<style>
    {!! file_get_contents(resource_path('css/landing/sections/inventario/hero.css')) !!}
</style>

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
                Gestiona tu stock, optimiza los niveles de reposición y elimina las
                pérdidas por desabastecimiento o exceso. Visibilidad completa sobre
                cada producto, en cada almacén, en todo momento.
            </p>

            <div class="inv-hero-actions">
                <a href="{{ route('register') }}" class="inv-btn-primary">Empieza Gratis</a>
                <a href="{{ route('about') }}" class="inv-btn-secondary">Ver demostración</a>
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
                        <div class="inv-stock-icon">📦</div>
                        <div class="inv-stock-info">
                            <div class="inv-stock-name">Producto A-201</div>
                            <div class="inv-stock-bar-track">
                                <div class="inv-stock-bar-fill" style="width: 85%;"></div>
                            </div>
                        </div>
                        <span class="inv-stock-qty">850 u.</span>
                    </div>
                    <div class="inv-stock-item">
                        <div class="inv-stock-icon">🔧</div>
                        <div class="inv-stock-info">
                            <div class="inv-stock-name">Componente B-047</div>
                            <div class="inv-stock-bar-track">
                                <div class="inv-stock-bar-fill" style="width: 42%;"></div>
                            </div>
                        </div>
                        <span class="inv-stock-qty">210 u.</span>
                    </div>
                    <div class="inv-stock-item">
                        <div class="inv-stock-icon">🏷️</div>
                        <div class="inv-stock-info">
                            <div class="inv-stock-name">SKU-C-933</div>
                            <div class="inv-stock-bar-track">
                                <div class="inv-stock-bar-fill" style="width: 95%;"></div>
                            </div>
                        </div>
                        <span class="inv-stock-qty">1,240 u.</span>
                    </div>
                    <div class="inv-stock-item">
                        <div class="inv-stock-icon">📋</div>
                        <div class="inv-stock-info">
                            <div class="inv-stock-name">Lote D-Serie 5</div>
                            <div class="inv-stock-bar-track">
                                <div class="inv-stock-bar-fill" style="width: 18%;"></div>
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
