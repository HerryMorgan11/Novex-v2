@push('styles')
@vite(['resources/css/landing/sections/recursos-humanos/metricas.css'])
@endpush

<section class="rh-metricas-section">
    <div class="rh-metricas-inner">
        <div class="rh-metricas-header">
            <p class="rh-metricas-pretitle">Impacto Real</p>
            <h2 class="rh-metricas-title">Resultados que hablan<br>por sí solos</h2>
            <p class="rh-metricas-subtitle">
                Empresas que usan Novex HR reportan mejoras significativas en sus
                procesos de gestión de personas desde el primer mes de implementación.
            </p>
        </div>

        <div class="rh-metricas-grid">
            <!-- Métrica 1 -->
            <div class="rh-metric-card">
                <div class="rh-metric-value">-30%</div>
                <div class="rh-metric-label">Tiempo de Contratación</div>
                <p class="rh-metric-desc">
                    Reducción en el tiempo promedio para cubrir vacantes gracias
                    a flujos de reclutamiento automatizados.
                </p>
            </div>

            <!-- Métrica 2 -->
            <div class="rh-metric-card">
                <div class="rh-metric-value">+94%</div>
                <div class="rh-metric-label">Satisfacción del Equipo</div>
                <p class="rh-metric-desc">
                    Índice de satisfacción promedio reportado por colaboradores
                    de empresas que usan Novex HR.
                </p>
            </div>

            <!-- Métrica 3 -->
            <div class="rh-metric-card">
                <div class="rh-metric-value">0</div>
                <div class="rh-metric-label">Errores en Nómina</div>
                <p class="rh-metric-desc">
                    Tasa de error en el procesamiento de nómina gracias a la
                    automatización y validación en tiempo real.
                </p>
            </div>
        </div>

        <!-- Testimonial -->
        <div class="rh-metricas-quote">
            <div class="rh-quote-mark">❝</div>
            <p class="rh-quote-text">
                "Implementar Novex HR fue un punto de inflexión para nuestro equipo. Pasamos de procesar
                la nómina en 3 días a hacerlo en menos de 2 horas. El tiempo que recuperamos ahora lo
                invertimos en lo que realmente importa: nuestra gente."
            </p>
            <div class="rh-quote-author">
                <img
                    class="rh-quote-avatar"
                    src="https://ui-avatars.com/api/?name=Elena+Martinez&background=1a1a24&color=fff"
                    alt="Elena Martinez"
                >
                <div>
                    <div class="rh-quote-name">Elena Martínez</div>
                    <div class="rh-quote-role">Directora de Recursos Humanos, Grupo Nexo</div>
                </div>
            </div>
        </div>
    </div>
</section>
