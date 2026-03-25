<style>
    {!! file_get_contents(resource_path('css/landing/sections/contabilidad/hero.css')) !!}
</style>

<section class="conta2-hero-section">
    <div class="conta2-hero-inner">
        <div class="conta2-hero-badge">
            <span class="conta2-hero-badge-dot"></span>
            Módulo de Finanzas
        </div>

        <h1 class="conta2-hero-title">
            Tu Arquitectura<br>
            Financiera <span>Inteligente</span>
        </h1>

        <p class="conta2-hero-desc">
            Transforma datos contables en decisiones estratégicas. Un sistema financiero
            moderno que automatiza la contabilidad, centraliza la facturación y ofrece
            visibilidad total sobre la salud económica de tu empresa.
        </p>

        <div class="conta2-hero-actions">
            <a href="{{ route('register') }}" class="conta2-btn-primary">Comenzar Gratis</a>
            <a href="{{ route('about') }}" class="conta2-btn-outline">Ver demostración</a>
        </div>

        <!-- Stats bar -->
        <div class="conta2-hero-stats">
            <div class="conta2-stat-item">
                <span class="conta2-stat-num">99<span>%</span></span>
                <span class="conta2-stat-label">Precisión contable</span>
            </div>
            <div class="conta2-stat-divider"></div>
            <div class="conta2-stat-item">
                <span class="conta2-stat-num">-40<span>%</span></span>
                <span class="conta2-stat-label">Tiempo de cierre</span>
            </div>
            <div class="conta2-stat-divider"></div>
            <div class="conta2-stat-item">
                <span class="conta2-stat-num">3x</span>
                <span class="conta2-stat-label">Velocidad en reportes</span>
            </div>
        </div>
    </div>
</section>
