@push('styles')
@vite(['resources/css/landing/sections/crm/hero.css'])
@endpush

<section class="crm-hero-section">
    <div class="crm-hero-inner">
        <!-- Texto izquierdo -->
        <div class="crm-hero-content-col">
            <div class="crm-hero-badge">
                <span class="crm-hero-badge-dot"></span>
                Módulo CRM &amp; Ventas
            </div>

            <h1 class="crm-hero-title">
                Convierte Cada<br>
                Oportunidad en un<br>
                <span>Cliente Fiel</span>
            </h1>

            <p class="crm-hero-desc">
                Novex CRM centraliza todos tus contactos, empresas y oportunidades de venta
                en un único lugar. Gestiona el pipeline comercial, haz seguimiento de cada
                negociación y sabe en todo momento en qué punto está cada cliente.
            </p>

            <div class="crm-hero-actions">
                <a href="{{ route('register') }}" class="crm-btn-primary">Empieza Gratis</a>
                <a href="{{ route('register') }}" class="crm-btn-secondary">Solicitar acceso</a>
            </div>
        </div>

        <!-- Tarjeta visual derecha: pipeline -->
        <div class="crm-hero-visual">
            <div class="crm-hero-card">
                <!-- Encabezado -->
                <div class="crm-card-title-row">
                    <span class="crm-card-heading">Pipeline de Ventas</span>
                    <span class="crm-card-value">$128,400</span>
                </div>

                <!-- Etapas del pipeline -->
                <div class="crm-pipeline">
                    <div class="crm-pipeline-stage">
                        <span class="crm-stage-label">Contacto</span>
                        <div class="crm-stage-bar-track">
                        <div class="crm-stage-bar-fill"></div>
                        </div>
                        <span class="crm-stage-count">24</span>
                    </div>
                    <div class="crm-pipeline-stage">
                        <span class="crm-stage-label">Calificado</span>
                        <div class="crm-stage-bar-track">
                        <div class="crm-stage-bar-fill"></div>
                        </div>
                        <span class="crm-stage-count">18</span>
                    </div>
                    <div class="crm-pipeline-stage">
                        <span class="crm-stage-label">Propuesta</span>
                        <div class="crm-stage-bar-track">
                        <div class="crm-stage-bar-fill"></div>
                        </div>
                        <span class="crm-stage-count">12</span>
                    </div>
                    <div class="crm-pipeline-stage">
                        <span class="crm-stage-label">Negociación</span>
                        <div class="crm-stage-bar-track">
                        <div class="crm-stage-bar-fill"></div>
                        </div>
                        <span class="crm-stage-count">8</span>
                    </div>
                    <div class="crm-pipeline-stage">
                        <span class="crm-stage-label">Cierre</span>
                        <div class="crm-stage-bar-track">
                        <div class="crm-stage-bar-fill"></div>
                        </div>
                        <span class="crm-stage-count">5</span>
                    </div>
                </div>

                <!-- Deal ganado -->
                <div class="crm-won-badge">
                    <div class="crm-won-dot"></div>
                    <span>3 negocios cerrados esta semana · +$14,200</span>
                </div>
            </div>
        </div>
    </div>
</section>
