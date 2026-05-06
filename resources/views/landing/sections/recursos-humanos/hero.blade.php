@push('styles')
@vite(['resources/css/landing/sections/recursos-humanos/hero.css'])
@endpush

<section class="rh-hero-section">
    <div class="rh-hero-inner">
        <!-- Texto izquierdo -->
        <div class="rh-hero-content-col">
            <div class="rh-hero-badge">
                <span class="rh-hero-badge-dot"></span>
                Módulo de Recursos Humanos
            </div>

            <h1 class="rh-hero-title">
                Gestiona el <span>Talento</span><br>
                que Mueve tu Empresa
            </h1>

            <p class="rh-hero-desc">
                Novex RRHH centraliza la gestión de tu equipo: fichas de empleados, nómina
                automatizada, control de ausencias y evaluación del desempeño. Todo en un
                solo sistema, integrado con el resto de módulos de Novex.
            </p>

            <div class="rh-hero-actions">
                <a href="{{ route('register') }}" class="rh-btn-primary">Empieza Gratis</a>
                <a href="{{ route('register') }}" class="rh-btn-secondary">Solicitar acceso</a>
            </div>
        </div>

        <!-- Tarjeta visual derecha -->
        <div class="rh-hero-visual">
            <div class="rh-hero-card">
                <!-- Encabezado de tarjeta -->
                <div class="rh-card-header-row">
                    <div class="rh-avatar-stack">
                        <div class="rh-avatar">JM</div>
                        <div class="rh-avatar">LS</div>
                        <div class="rh-avatar">RA</div>
                        <div class="rh-avatar">+12</div>
                    </div>
                    <div>
                        <div class="rh-card-label">Equipo Activo</div>
                        <div class="rh-card-sublabel">15 colaboradores</div>
                    </div>
                </div>

                <!-- Estadísticas en cuadrícula -->
                <div class="rh-stat-row">
                    <div class="rh-stat-box">
                        <div class="rh-stat-label">Retención</div>
                        <div class="rh-stat-val orange">94%</div>
                    </div>
                    <div class="rh-stat-box">
                        <div class="rh-stat-label">Satisfacción</div>
                        <div class="rh-stat-val">4.8 / 5</div>
                    </div>
                </div>

                <!-- Barras de progreso -->
                <div class="rh-progress-section">
                    <div class="rh-progress-label">
                        <span>Nómina procesada</span>
                        <span>98%</span>
                    </div>
                    <div class="rh-progress-track">
                        <div class="rh-progress-fill"></div>
                    </div>

                    <div class="rh-progress-label">
                        <span>Vacantes cubiertas</span>
                        <span>75%</span>
                    </div>
                    <div class="rh-progress-track">
                        <div class="rh-progress-fill"></div>
                    </div>

                    <div class="rh-progress-label">
                        <span>Formaciones completadas</span>
                        <span>82%</span>
                    </div>
                    <div class="rh-progress-track">
                        <div class="rh-progress-fill"></div>
                    </div>
                </div>
            </div>

            <!-- Floating stat badge -->
            <div class="rh-floating-stat">
                <div class="rh-floating-icon">
                    <iconify-icon icon="fluent:data-trending-24-regular"></iconify-icon>
                </div>
                <div>
                    <div class="rh-floating-value">-30%</div>
                    <div class="rh-floating-desc">Tiempo de contratación</div>
                </div>
            </div>
        </div>
    </div>
</section>
