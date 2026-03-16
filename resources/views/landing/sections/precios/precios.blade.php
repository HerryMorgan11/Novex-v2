<section class="pricing-section">
    <div class="pricing-header">
        <span class="pricing-subtitle">NUESTROS PLANES</span>
        <h2 class="pricing-title">Escala tu negocio con el plan adecuado</h2>
        <p class="pricing-description">Simple, transparente y diseñado para crecer contigo. Sin cargos ocultos ni sorpresas.</p>
        
        <div class="billing-toggle">
            <span class="billing-label active">Mensual</span>
            <div class="toggle-switch">
                <div class="toggle-knob"></div>
            </div>
            <span class="billing-label highlight">Anual (20% Dto.)</span>
        </div>
    </div>

    <div class="pricing-cards">
        <!-- Starter Plan -->
        <div class="pricing-card">
            <div class="card-header">
                <h3>Starter</h3>
                <p>Perfecto para individuos y pequeños proyectos.</p>
            </div>
            <div class="card-price">
                <span class="price">$0</span>
                <span class="period">/mes</span>
            </div>
            <ul class="card-features">
                <li>
                   <iconify-icon class="feature-icon" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    Hasta 3 proyectos activos
                </li>
                <li>
                  <iconify-icon class="feature-icon" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    Funciones básicas de análisis
                </li>
                <li>
                  <iconify-icon class="feature-icon" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    Soporte por comunidad
                </li>
                <li class="disabled">
                 <iconify-icon icon="bi:x-circle-fill"></iconify-icon>
                    Reportes personalizados
                </li>
            </ul>
            <a href="#" class="pricing-btn btn-outline">Empezar Gratis</a>
        </div>

        <!-- Professional Plan -->
        <div class="pricing-card pro-card">
            <div class="popular-badge">MÁS POPULAR</div>
            <div class="card-header">
                <h3>Professional</h3>
                <p>Para equipos que necesitan potencia y flexibilidad.</p>
            </div>
            <div class="card-price">
                <span class="price">$49</span>
                <span class="period">/mes</span>
            </div>
            <ul class="card-features">
                <li>
                   <iconify-icon class="feature-icon check-white" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    Todo en Starter
                </li>
                <li>
                    <iconify-icon class="feature-icon check-white" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    Proyectos ilimitados
                </li>
                <li>
                    <iconify-icon class="feature-icon check-white" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    Análisis avanzados IA
                </li>
                <li>
                    <iconify-icon class="feature-icon check-white" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    Soporte prioritario 24/7
                </li>
                <li>
                    <iconify-icon class="feature-icon check-white" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    Reportes personalizados
                </li>
            </ul>
            <a href="#" class="pricing-btn btn-white">Comenzar ahora</a>
        </div>

        <!-- Enterprise Plan -->
        <div class="pricing-card">
            <div class="card-header">
                <h3>Enterprise</h3>
                <p>Soluciones robustas para grandes corporaciones.</p>
            </div>
            <div class="card-price">
                <span class="price">$199</span>
                <span class="period">/mes</span>
            </div>
            <ul class="card-features">
                <li>
                   <iconify-icon class="feature-icon" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    Todo en Professional
                </li>
                <li>
                    <iconify-icon class="feature-icon" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    Seguridad de grado bancario
                </li>
                <li>
                    <iconify-icon class="feature-icon" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    API dedicada e integraciones
                </li>
                <li>
                    <iconify-icon class="feature-icon" icon="teenyicons:tick-circle-solid"></iconify-icon>
                    Account Manager dedicado
                </li>
            </ul>
            <a href="#" class="pricing-btn btn-outline">Contactar Ventas</a>
        </div>
    </div>
</section>

<script>
    const toggle = document.querySelector('.toggle-switch');
    const labels = document.querySelectorAll('.billing-label');
    const prices = document.querySelectorAll('.price');
    const monthly = ['0', '49', '199'];
    const annual = ['0', '39', '159'];

    if (toggle) {
        toggle.onclick = () => {
            const isAnual = toggle.classList.toggle('active');
            
            labels[0].classList.toggle('highlight', !isAnual);
            labels[1].classList.toggle('highlight', isAnual);
            
            prices.forEach((price, index) => {
                price.textContent = '$' + (isAnual ? annual[index] : monthly[index]);
            });
        };
    }
</script>
