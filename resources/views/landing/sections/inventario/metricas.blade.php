<style>
    {!! file_get_contents(resource_path('css/landing/sections/inventario/metricas.css')) !!}
</style>

<section class="inv-metricas-section">
    <div class="inv-metricas-inner">
        <div class="inv-metricas-header">
            <p class="inv-metricas-pretitle">Impacto en Operaciones</p>
            <h2 class="inv-metricas-title">Números que demuestran<br>el cambio real</h2>
            <p class="inv-metricas-subtitle">
                Las empresas que implementan Novex Inventario transforman su operación
                logística y reducen costes significativamente desde el primer trimestre.
            </p>
        </div>

        <div class="inv-metricas-grid">
            <!-- Métrica 1 -->
            <div class="inv-metric-card">
                <div class="inv-metric-icon">
                    <iconify-icon icon="mdi:check-circle-outline"></iconify-icon>
                </div>
                <div class="inv-metric-value">99.8%</div>
                <div class="inv-metric-label">Precisión de Inventario</div>
                <p class="inv-metric-desc">
                    Exactitud en el recuento de existencias comparado con el promedio
                    del sector que ronda el 75%.
                </p>
            </div>

            <!-- Métrica 2 -->
            <div class="inv-metric-card">
                <div class="inv-metric-icon">
                    <iconify-icon icon="mdi:trending-down"></iconify-icon>
                </div>
                <div class="inv-metric-value">-50%</div>
                <div class="inv-metric-label">Reducción de Pérdidas</div>
                <p class="inv-metric-desc">
                    Disminución en pérdidas por caducidad, robo o extravío gracias
                    al tracking en tiempo real.
                </p>
            </div>

            <!-- Métrica 3 -->
            <div class="inv-metric-card">
                <div class="inv-metric-icon">
                    <iconify-icon icon="mdi:office-building-outline"></iconify-icon>
                </div>
                <div class="inv-metric-value">100+</div>
                <div class="inv-metric-label">Almacenes Gestionados</div>
                <p class="inv-metric-desc">
                    Empresas con múltiples almacenes gestionan todas sus ubicaciones
                    desde un único panel centralizado.
                </p>
            </div>
        </div>

        <!-- CTA band -->
        <div class="inv-metricas-cta-band">
            <div class="inv-metricas-cta-text">
                <h3>¿Quieres ver Novex Inventario en acción?</h3>
                <p>Programa una demo personalizada con nuestro equipo en menos de 24 horas.</p>
            </div>
            <a href="{{ route('register') }}" class="inv-metricas-cta-band-btn">
                Solicitar Demo Gratuita
            </a>
        </div>
    </div>
</section>
