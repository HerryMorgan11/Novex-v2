<style>
    {!! file_get_contents(resource_path('views/landing/sections/contabilidad/contabilidad.css')) !!}
</style>

<div class="contabilidad-wrapper">
    <!-- Hero Section -->
    <section class="conta-hero">
        <div class="conta-hero-subtitle">SOFTWARE FINANCIERO 2.0</div>
        <h1>
            La Arquitectura Financiera que tu <br>
            <span class="highlight">Negocio Merece</span>
        </h1>
        <p>Transformamos datos contables en narrativas de crecimiento. Un ledger moderno para mentes que construuyen el futuro.</p>
        <div class="conta-hero-buttons">
            <button class="conta-btn-primary">Comenzar Gratis</button>
        </div>
    </section>

    <!-- Features Section -->
    <section class="conta-features">
        <div class="conta-features-header">
            <h2>Componentes de una Estructura Maestra</h2>
            <p>Diseñada con precisión milimetral para eliminar el ruido y resaltar lo que realmente importa en tu balance.</p>
        </div>

        <div class="conta-grid">
            <!-- Card 1 -->
            <div class="conta-card">
                <div class="conta-icon-wrapper">
                    <iconify-icon icon="fluent:flowchart-20-regular" style="font-size: 24px;"></iconify-icon>
                </div>
                <h3>Catálogo de Cuentas Maestro</h3>
                <p>Estructura jerárquica intuitiva que se adapta al ADN de tu empresa, no al revés.</p>
                <div class="conta-mockup">
                    <div class="mockup-item">
                        <div><span class="dot-indicator"></span> 1000 - Activos Corrientes</div>
                        <span class="amount-red">$450,230.00</span>
                    </div>
                    <div class="mockup-item level-2">
                        <div><span class="dot-indicator faded"></span> 1010 - Caja y Bancos</div>
                        <span class="amount-dark">$125,000.00</span>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="conta-card orange">
                <div class="conta-icon-wrapper">
                    <iconify-icon icon="clarity:receipt-line" style="font-size: 24px;"></iconify-icon>
                </div>
                <h3>Gestión de Facturas Inteligente</h3>
                <p>Monitoreo en tiempo real con estados visuales claros. Adiós a los procesos manuales.</p>
                <div class="mockup-glass">
                    <div class="glass-header">
                        <span>INVOICE #8829</span>
                        <span class="glass-badge">PAID</span>
                    </div>
                    <div class="glass-progress">
                        <div class="glass-progress-bar"></div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="conta-card full-width">
                <div class="full-width-content">
                    <!-- Text Side -->
                    <div class="full-width-text">
                        <div class="conta-icon-wrapper" style="background: #fff0f3; color: #d1225b;">
                            <iconify-icon icon="fluent:data-trending-24-regular" style="font-size: 24px;"></iconify-icon>
                        </div>
                        <h3>Reportes P&L de Alto Nivel</h3>
                        <p>Narrativas visuales que explican el "por qué" detrás de los números. Presentaciones listas para el Board en un clic.</p>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <h4>+24%</h4>
                                <p>Margen Operativo</p>
                            </div>
                            <div class="stat-item" style="border-left: 2px solid #eee; padding-left: 20px;">
                                <h4 style="color: #df5a3c;">-$12k</h4>
                                <p>Optimización Fiscal</p>
                            </div>
                        </div>
                    </div>
                    <!-- Visual Side -->
                    <div class="full-width-visual">
                        <div class="dashboard-mockup">
                            <div class="dashboard-inner">
                                <div class="dash-header"></div>
                                <div class="dash-body">
                                    <div class="dash-bar" style="height: 30%;"></div>
                                    <div class="dash-bar" style="height: 45%;"></div>
                                    <div class="dash-bar" style="height: 60%;"></div>
                                    <div class="dash-bar" style="height: 35%;"></div>
                                    <div class="dash-bar" style="height: 80%;"></div>
                                    <div class="dash-bar active" style="height: 100%;"></div>
                                    <div class="dash-bar" style="height: 50%;"></div>
                                </div>
                                <div class="dash-header" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="conta-testimonial">
        <div class="quote-icon">❞</div>
        <blockquote>
            "Novex no es solo un software de contabilidad; es el editor en jefe de nuestras finanzas. Ha cambiado la forma en que visualizamos el éxito."
        </blockquote>
        <div class="conta-author">
            <img src="https://ui-avatars.com/api/?name=Javier+y+David&background=1a1a24&color=fff" alt="Javier y David">
            <div class="conta-author-info">
                <span class="conta-author-name">Javier y Davids</span>
                <span class="conta-author-role">Fundadores, Novex</span>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="conta-cta">
        <h2>Únete a la nueva era de la contabilidad</h2>
        <p>Diseña el futuro financiero de tu empresa con la herramienta más sofisticada del mercado.</p>
        <button class="conta-btn-primary">Comenzar Ahora</button>
        <br>
        <a href="#" class="conta-cta-link">Agendar una consultoría →</a>
    </section>
</div>

<script>
    {!! file_get_contents(resource_path('views/landing/sections/contabilidad/contabilidad.js')) !!}
</script>
