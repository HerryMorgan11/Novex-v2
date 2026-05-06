import Chart from 'chart.js/auto';

// ─── Theme helpers ────────────────────────────────────────────────────────────
const isDark = () => document.documentElement.classList.contains('dark-theme');

const palette = () =>
    isDark()
        ? {
              blue: 'rgba(147,197,253,.85)',
              green: 'rgba(134,239,172,.85)',
              amber: 'rgba(253,230,138,.85)',
              rose: 'rgba(253,164,175,.85)',
              violet: 'rgba(196,181,253,.85)',
              sky: 'rgba(125,211,252,.85)',
              teal: 'rgba(94,234,212,.85)',
              slate: 'rgba(148,163,184,.85)',
              grid: 'rgba(255,255,255,.06)',
              text: '#a1a1aa',
          }
        : {
              blue: '#3b82f6',
              green: '#22c55e',
              amber: '#f59e0b',
              rose: '#f43f5e',
              violet: '#8b5cf6',
              sky: '#0ea5e9',
              teal: '#14b8a6',
              slate: '#64748b',
              grid: 'rgba(0,0,0,.06)',
              text: '#64748b',
          };

const baseFont = () => ({
    family:
        getComputedStyle(document.documentElement).getPropertyValue('--font').trim() || 'system-ui',
    size: 11,
});

// ─── Shared chart defaults ────────────────────────────────────────────────────
function sharedDefaults() {
    const p = palette();
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: p.text,
                    font: baseFont(),
                    boxWidth: 10,
                    padding: 14,
                },
            },
            tooltip: {
                backgroundColor: isDark() ? '#3b1821' : '#ffffff',
                titleColor: isDark() ? '#fce7f3' : '#0f172a',
                bodyColor: isDark() ? '#a1a1aa' : '#64748b',
                borderColor: isDark() ? '#562731' : '#e2e8f0',
                borderWidth: 1,
                padding: 10,
                cornerRadius: 8,
            },
        },
        scales: {
            x: {
                ticks: { color: p.text, font: baseFont() },
                grid: { color: p.grid },
                border: { display: false },
            },
            y: {
                ticks: { color: p.text, font: baseFont() },
                grid: { color: p.grid },
                border: { display: false },
                beginAtZero: true,
            },
        },
    };
}

// ─── Chart instances registry ─────────────────────────────────────────────────
const charts = {};

function destroyAll() {
    Object.values(charts).forEach(c => c?.destroy());
}

// ─── Chart builders ───────────────────────────────────────────────────────────
function buildMovimientosChart(data) {
    const ctx = document.getElementById('chartMovimientos');
    if (!ctx) return;

    charts.movimientos?.destroy();

    const p = palette();
    charts.movimientos = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Movimientos',
                    data: data.data,
                    borderColor: p.blue,
                    backgroundColor: isDark() ? 'rgba(147,197,253,.12)' : 'rgba(59,130,246,.08)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: p.blue,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                },
            ],
        },
        options: {
            ...sharedDefaults(),
            plugins: {
                ...sharedDefaults().plugins,
                legend: { display: false },
            },
        },
    });
}

function buildTransportesChart(data) {
    const ctx = document.getElementById('chartTransportes');
    if (!ctx) return;

    charts.transportes?.destroy();

    if (!data.labels?.length) {
        ctx.closest('.db-chart-canvas-wrap').innerHTML =
            '<p style="color:var(--muted);font-size:.8rem;text-align:center;padding-top:80px">Sin datos</p>';
        return;
    }

    const p = palette();
    const colors = [p.blue, p.green, p.amber, p.rose, p.violet, p.sky, p.teal];

    charts.transportes = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels,
            datasets: [
                {
                    data: data.data,
                    backgroundColor: colors.slice(0, data.labels.length),
                    borderWidth: 0,
                    hoverOffset: 4,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: palette().text,
                        font: baseFont(),
                        boxWidth: 10,
                        padding: 10,
                    },
                },
                tooltip: sharedDefaults().plugins.tooltip,
            },
        },
    });
}

function buildExpedicionesChart(data) {
    const ctx = document.getElementById('chartExpediciones');
    if (!ctx) return;

    charts.expediciones?.destroy();

    if (!data.labels?.length) {
        ctx.closest('.db-chart-canvas-wrap').innerHTML =
            '<p style="color:var(--muted);font-size:.8rem;text-align:center;padding-top:80px">Sin datos</p>';
        return;
    }

    const p = palette();
    const colors = [p.teal, p.blue, p.violet, p.amber, p.rose];

    charts.expediciones = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Expediciones',
                    data: data.data,
                    backgroundColor: colors.slice(0, data.labels.length),
                    borderRadius: 6,
                    borderSkipped: false,
                },
            ],
        },
        options: {
            ...sharedDefaults(),
            plugins: {
                ...sharedDefaults().plugins,
                legend: { display: false },
            },
        },
    });
}

// ─── Init all charts ──────────────────────────────────────────────────────────
function initCharts(chartData) {
    buildMovimientosChart(chartData.movimientos);
    buildTransportesChart(chartData.transportes_por_estado);
    buildExpedicionesChart(chartData.expediciones_por_estado);
}

// ─── KPI metric update ────────────────────────────────────────────────────────
function updateMetrics(metrics) {
    const map = {
        'kpi-productos-activos': metrics.productos_activos,
        'kpi-productos-borrador': metrics.productos_borrador,
        'kpi-stock-total':
            metrics.stock_total !== undefined
                ? Number(metrics.stock_total).toLocaleString('es-ES', { maximumFractionDigits: 0 })
                : null,
        'kpi-transportes-pendientes': metrics.transportes_pendientes,
        'kpi-expediciones-activas': metrics.expediciones_activas,
        'kpi-lotes-almacenados': metrics.lotes_almacenados,
        'kpi-recordatorios-activos': metrics.recordatorios_activos,
        'kpi-notas-total': metrics.notas_total,
    };

    Object.entries(map).forEach(([id, val]) => {
        const el = document.getElementById(id);
        if (el && val !== null && val !== undefined) {
            el.textContent = val;
        }
    });
}

// ─── Period filter ────────────────────────────────────────────────────────────
function initPeriodFilter() {
    const btns = document.querySelectorAll('.db-period-tabs button');
    if (!btns.length) return;

    btns.forEach(btn => {
        btn.addEventListener('click', async () => {
            const period = btn.dataset.period;

            btns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Update URL without reload
            const url = new URL(window.location.href);
            url.searchParams.set('period', period);
            window.history.replaceState({}, '', url.toString());

            await fetchAndUpdate(period);
        });
    });
}

async function fetchAndUpdate(period) {
    const url = new URL(window.location.origin + '/app/chart-data');
    url.searchParams.set('period', period);

    try {
        const res = await fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
            },
        });
        if (!res.ok) return;

        const json = await res.json();

        destroyAll();
        initCharts(json.chartData);
        updateMetrics(json.metrics);
    } catch (e) {
        console.warn('[dashboard] Error al actualizar datos:', e);
    }
}

// ─── Theme change listener ────────────────────────────────────────────────────
function watchTheme() {
    const observer = new MutationObserver(() => {
        const currentData = window.__dashboardChartData;
        if (currentData) {
            destroyAll();
            initCharts(currentData);
        }
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
}

// ─── Entry point ──────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const raw = document.getElementById('dashboard-chart-data');
    if (!raw) return;

    try {
        const data = JSON.parse(raw.textContent);
        window.__dashboardChartData = data;
        initCharts(data);
        initPeriodFilter();
        watchTheme();
    } catch (e) {
        console.warn('[dashboard] No se pudieron cargar los datos de gráficas', e);
    }
});
