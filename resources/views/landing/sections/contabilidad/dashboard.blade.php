@push('styles')
@vite(['resources/css/landing/sections/contabilidad/dashboard.css'])
@endpush

<section class="conta2-dash-section">
    <div class="conta2-dash-inner">
        <!-- Texto izquierdo -->
        <div class="conta2-dash-text">
            <p class="conta2-dash-pretitle">Visibilidad de Negocio</p>
            <h2 class="conta2-dash-title">
                Conoce el estado real<br>de tu empresa en todo momento
            </h2>
            <p class="conta2-dash-desc">
                El módulo de Finanzas de Novex centraliza toda la información económica
                de tu empresa en un único panel. Ve ingresos, gastos, márgenes y cobros
                pendientes en tiempo real, sin esperar al cierre del mes.
            </p>
            <ul class="conta2-dash-features">
                <li>
                    <span class="chk">✓</span>
                    KPIs financieros actualizados en tiempo real
                </li>
                <li>
                    <span class="chk">✓</span>
                    Control de ingresos, gastos y márgenes
                </li>
                <li>
                    <span class="chk">✓</span>
                    Alertas cuando los valores salen del umbral
                </li>
                <li>
                    <span class="chk">✓</span>
                    Exportación de informes con un clic
                </li>
            </ul>
        </div>

        <!-- Panel de KPIs -->
        <div class="conta2-kpi-panel">
            <div class="conta2-kpi-panel-title">Resumen Financiero</div>

            <div class="conta2-kpi-grid">
                <div class="conta2-kpi-card">
                    <div class="conta2-kpi-card-icon">
                        <iconify-icon icon="mdi:trending-up"></iconify-icon>
                    </div>
                    <div class="conta2-kpi-card-label">Ingresos del mes</div>
                    <div class="conta2-kpi-card-value positive">$284.500</div>
                    <div class="conta2-kpi-card-change">+12% vs mes anterior</div>
                </div>

                <div class="conta2-kpi-card">
                    <div class="conta2-kpi-card-icon negative-icon">
                        <iconify-icon icon="mdi:trending-down"></iconify-icon>
                    </div>
                    <div class="conta2-kpi-card-label">Gastos del mes</div>
                    <div class="conta2-kpi-card-value">$198.200</div>
                    <div class="conta2-kpi-card-change">-5% vs mes anterior</div>
                </div>

                <div class="conta2-kpi-card">
                    <div class="conta2-kpi-card-icon accent-icon">
                        <iconify-icon icon="mdi:percent-outline"></iconify-icon>
                    </div>
                    <div class="conta2-kpi-card-label">Margen bruto</div>
                    <div class="conta2-kpi-card-value">+24%</div>
                    <div class="conta2-kpi-card-change">Sobre ingresos totales</div>
                </div>

                <div class="conta2-kpi-card warning-card">
                    <div class="conta2-kpi-card-icon warning-icon">
                        <iconify-icon icon="mdi:clock-alert-outline"></iconify-icon>
                    </div>
                    <div class="conta2-kpi-card-label">Cobros pendientes</div>
                    <div class="conta2-kpi-card-value warning">$12.000</div>
                    <div class="conta2-kpi-card-change">3 facturas vencidas</div>
                </div>

                <div class="conta2-kpi-card">
                    <div class="conta2-kpi-card-icon">
                        <iconify-icon icon="mdi:bank-outline"></iconify-icon>
                    </div>
                    <div class="conta2-kpi-card-label">Flujo de caja</div>
                    <div class="conta2-kpi-card-value positive">$86.300</div>
                    <div class="conta2-kpi-card-change">Saldo disponible</div>
                </div>

                <div class="conta2-kpi-card">
                    <div class="conta2-kpi-card-icon">
                        <iconify-icon icon="mdi:receipt-text-outline"></iconify-icon>
                    </div>
                    <div class="conta2-kpi-card-label">Facturas emitidas</div>
                    <div class="conta2-kpi-card-value">48</div>
                    <div class="conta2-kpi-card-change">Este mes</div>
                </div>
            </div>
        </div>
    </div>
</section>
