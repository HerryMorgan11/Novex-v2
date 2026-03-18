<section class="pricing-section" id="precios">
    <div class="pricing-container">
        <div class="pricing-header">
            <h4 class="pricing-subtitle">NUESTROS PLANES</h4>
            <h2 class="pricing-title">Escala tu negocio con el plan adecuado</h2>
            <p class="pricing-desc">Simple, transparente y diseñado para crecer contigo. Sin cargos ocultos ni sorpresas.</p>
        </div>

        <div class="pricing-toggle-wrapper">
            <span class="toggle-label" id="label-mensual">Mensual</span>
            <label class="toggle-switch">
                <input type="checkbox" id="pricing-toggle">
                <span class="slider round"></span>
            </label>
            <span class="toggle-label" id="label-anual">Anual <span class="discount">(20% Dto.)</span></span>
        </div>

        <div class="pricing-cards">
            <!-- Starter Card -->
            <div class="pricing-card">
                <div class="card-header">
                    <h3>Starter</h3>
                    <p>Perfecto para individuos y pequeños proyectos.</p>
                </div>
                <div class="card-price">
                    <span class="currency">$</span>
                    <span class="amount" data-mensual="0" data-anual="0">0</span>
                    <span class="period" data-mensual="/mes" data-anual="/año">/mes</span>
                </div>
                <ul class="card-features">
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> Hasta 3 proyectos activos</li>
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> Funciones básicas de análisis</li>
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> Soporte por comunidad</li>
                    <li class="disabled"><iconify-icon icon="charm:circle-cross" class="feature-icon cross"></iconify-icon> Reportes personalizados</li>
                </ul>
                <a href="#" class="btn-pricing btn-outline">Empezar Gratis</a>
            </div>

            <!-- Professional Card -->
            <div class="pricing-card popular">
                <div class="popular-badge">MÁS POPULAR</div>
                <div class="card-header">
                    <h3>Professional</h3>
                    <p>Para equipos que necesitan potencia y flexibilidad.</p>
                </div>
                <div class="card-price">
                    <span class="currency">$</span>
                    <span class="amount" data-mensual="49" data-anual="39">49</span>
                    <span class="period" data-mensual="/mes" data-anual="/año">/mes</span>
                </div>
                <ul class="card-features">
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> Todo en Starter</li>
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> Proyectos ilimitados</li>
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> Análisis avanzados IA</li>
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> Soporte prioritario 24/7</li>
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> Reportes personalizados</li>
                </ul>
                <a href="#" class="btn-pricing btn-solid">Comenzar ahora</a>
            </div>

            <!-- Enterprise Card -->
            <div class="pricing-card">
                <div class="card-header">
                    <h3>Enterprise</h3>
                    <p>Soluciones robustas para grandes corporaciones.</p>
                </div>
                <div class="card-price">
                    <span class="currency">$</span>
                    <span class="amount" data-mensual="199" data-anual="159">199</span>
                    <span class="period" data-mensual="/mes" data-anual="/año">/mes</span>
                </div>
                <ul class="card-features">
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> Todo en Professional</li>
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> Seguridad de grado bancario</li>
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> API dedicada e integraciones</li>
                    <li><iconify-icon icon="charm:circle-tick" class="feature-icon"></iconify-icon> Account Manager dedicado</li>
                </ul>
                <a href="#" class="btn-pricing btn-outline">Contactar Ventas</a>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggle = document.getElementById('pricing-toggle');
        const amounts = document.querySelectorAll('.pricing-card .amount');
        const periods = document.querySelectorAll('.pricing-card .period');
        const labelMensual = document.getElementById('label-mensual');
        const labelAnual = document.getElementById('label-anual');

        if(toggle) {
            toggle.addEventListener('change', (e) => {
                const isAnual = e.target.checked;
                
                if(isAnual) {
                    labelMensual.style.fontWeight = 'normal';
                    labelAnual.style.fontWeight = '700';
                } else {
                    labelMensual.style.fontWeight = '700';
                    labelAnual.style.fontWeight = 'normal';
                }

                amounts.forEach(amount => {
                    amount.textContent = isAnual ? amount.getAttribute('data-anual') : amount.getAttribute('data-mensual');
                });

                periods.forEach(period => {
                    period.textContent = isAnual ? period.getAttribute('data-anual') : period.getAttribute('data-mensual');
                });
            });
        }
    });
</script>
