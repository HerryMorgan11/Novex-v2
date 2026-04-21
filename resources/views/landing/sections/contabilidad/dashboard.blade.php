@push('styles')
@vite(['resources/css/landing/sections/contabilidad/dashboard.css'])
@endpush

<section class="conta2-dash-section">
    <div class="conta2-dash-inner">
        <!-- Texto izquierdo -->
        <div class="conta2-dash-text">
            <p class="conta2-dash-pretitle">Dashboard Financiero</p>
            <h2 class="conta2-dash-title">
                Visibilidad total sobre<br>la salud de tu negocio
            </h2>
            <p class="conta2-dash-desc">
                Un panel centralizado que muestra en tiempo real el estado financiero
                de tu empresa. Desde el flujo de caja hasta los márgenes por producto,
                toda la información que necesitas para tomar decisiones con confianza.
            </p>
            <ul class="conta2-dash-features">
                <li>
                    <span class="chk">✓</span>
                    Actualización en tiempo real sin necesidad de refrescar
                </li>
                <li>
                    <span class="chk">✓</span>
                    KPIs financieros personalizables por rol y departamento
                </li>
                <li>
                    <span class="chk">✓</span>
                    Alertas inteligentes cuando los valores se salen del umbral
                </li>
                <li>
                    <span class="chk">✓</span>
                    Exportación de informes con un solo clic
                </li>
            </ul>
        </div>

        <!-- Dashboard mockup -->
        <div class="conta2-dashboard-mock">
            <!-- Barra de título tipo macOS -->
            <div class="conta2-mock-topbar">
                <div class="conta2-mock-dot red"></div>
                <div class="conta2-mock-dot yellow"></div>
                <div class="conta2-mock-dot green"></div>
                <div class="conta2-mock-tabs">
                    <button class="conta2-mock-tab active">Resumen</button>
                    <button class="conta2-mock-tab">Ingresos</button>
                    <button class="conta2-mock-tab">Gastos</button>
                </div>
            </div>

            <!-- KPI Row -->
            <div class="conta2-kpi-row">
                <div class="conta2-kpi-box">
                    <div class="conta2-kpi-label">Ingresos</div>
                    <div class="conta2-kpi-value">$284k</div>
                </div>
                <div class="conta2-kpi-box">
                    <div class="conta2-kpi-label">Margen</div>
                    <div class="conta2-kpi-value positive">+24%</div>
                </div>
                <div class="conta2-kpi-box">
                    <div class="conta2-kpi-label">Pendiente</div>
                    <div class="conta2-kpi-value negative">-$12k</div>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="conta2-chart-area">
                <div class="conta2-bar"></div>
                <div class="conta2-bar"></div>
                <div class="conta2-bar"></div>
                <div class="conta2-bar"></div>
                <div class="conta2-bar"></div>
                <div class="conta2-bar accent"></div>
                <div class="conta2-bar"></div>
                <div class="conta2-bar"></div>
                <div class="conta2-bar"></div>
                <div class="conta2-bar"></div>
                <div class="conta2-bar"></div>
                <div class="conta2-bar"></div>
            </div>

            <!-- Recent transactions -->
            <div class="conta2-txn-list">
                <div class="conta2-txn-item">
                    <span class="conta2-txn-name">
                        <span class="conta2-txn-icon">📥</span>
                        Cobro cliente #8829
                    </span>
                    <span class="conta2-txn-amount green">+$4,500</span>
                </div>
                <div class="conta2-txn-item">
                    <span class="conta2-txn-name">
                        <span class="conta2-txn-icon">📤</span>
                        Pago proveedor Nexo
                    </span>
                    <span class="conta2-txn-amount red">-$1,200</span>
                </div>
                <div class="conta2-txn-item">
                    <span class="conta2-txn-name">
                        <span class="conta2-txn-icon">📥</span>
                        Factura #9001
                    </span>
                    <span class="conta2-txn-amount green">+$8,200</span>
                </div>
            </div>
        </div>
    </div>
</section>
