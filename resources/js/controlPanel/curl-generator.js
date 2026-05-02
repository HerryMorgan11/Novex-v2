/**
 * curl-generator.js
 * Generador interactivo de cURL para el API de Transportes.
 * Lee el tenantId y apiToken inyectados en el DOM desde el servidor.
 */

const builderEl = document.querySelector('[data-curl-tenant-id]');
if (!builderEl) {
    // La sección empresa no está activa en este contexto
} else {
    const TENANT_ID = builderEl.dataset.curlTenantId ?? '';
    const API_TOKEN = builderEl.dataset.curlApiToken ?? '';
    const BASE_URL = (builderEl.dataset.curlBaseUrl ?? '').replace(/\/$/, '');

    // ── Plantillas de ejemplo ────────────────────────────────────────────────
    const TEMPLATES = {
        tornillos: {
            referencia: 'TR-EXT-001',
            proveedor: 'Metalurgia S.A.',
            origen: 'Madrid',
            destino: 'Barcelona',
            placa: '1234ABC',
            transportista: 'Juan García López',
            fecha_prevista: '2026-05-15T10:00',
            observaciones: 'Producto frágil - manipular con cuidado',
            lineas: [
                {
                    referencia_producto: 'SKU-001',
                    nombre: 'Tornillo M8 x 25',
                    cantidad: 1000,
                    unidad: 'piezas',
                },
                {
                    referencia_producto: 'SKU-002',
                    nombre: 'Tuerca M8',
                    cantidad: 500,
                    unidad: 'piezas',
                },
            ],
        },
        paletas: {
            referencia: 'TR-EXT-002',
            proveedor: 'LogiPak S.L.',
            origen: 'Valencia',
            destino: 'Sevilla',
            placa: '5678DEF',
            transportista: 'Pedro Martínez Ruiz',
            fecha_prevista: '2026-05-20T09:00',
            observaciones: '',
            lineas: [
                {
                    referencia_producto: 'SKU-PAL-01',
                    nombre: 'Paleta estándar 120x80',
                    cantidad: 50,
                    unidad: 'unidades',
                },
                {
                    referencia_producto: 'SKU-PAL-02',
                    nombre: 'Film retráctil 500m',
                    cantidad: 100,
                    unidad: 'rollos',
                },
            ],
        },
        quimicos: {
            referencia: 'TR-EXT-003',
            proveedor: 'QuimiSuply S.A.',
            origen: 'Bilbao',
            destino: 'Zaragoza',
            placa: '9012GHI',
            transportista: 'Ana Rodríguez Vega',
            fecha_prevista: '2026-05-25T08:00',
            observaciones: 'Mercancía peligrosa - ADR clase 3',
            lineas: [
                {
                    referencia_producto: 'QM-2401',
                    nombre: 'Disolvente industrial 20L',
                    cantidad: 100,
                    unidad: 'bidones',
                },
                {
                    referencia_producto: 'QM-2402',
                    nombre: 'Acetona técnica 5L',
                    cantidad: 200,
                    unidad: 'botellas',
                },
            ],
        },
    };

    // ── Helpers ──────────────────────────────────────────────────────────────

    function val(id) {
        return document.getElementById(id)?.value.trim() ?? '';
    }

    function nullIfEmpty(str) {
        return str === '' ? null : str;
    }

    /** Construye el payload JSON a partir del formulario actual. */
    function buildPayload() {
        const lineas = [];
        document.querySelectorAll('.curl-line-row').forEach(row => {
            const nombre = row.querySelector('[data-line-nombre]').value.trim();
            if (!nombre) return; // saltar líneas sin nombre
            lineas.push({
                referencia_producto: nullIfEmpty(row.querySelector('[data-line-ref]').value.trim()),
                nombre,
                cantidad: parseFloat(row.querySelector('[data-line-cantidad]').value) || 1,
                unidad: nullIfEmpty(row.querySelector('[data-line-unidad]').value.trim()),
            });
        });

        const payload = { lineas };

        const fields = {
            referencia: nullIfEmpty(val('curl-referencia')),
            proveedor: nullIfEmpty(val('curl-proveedor')),
            origen: nullIfEmpty(val('curl-origen')),
            destino: nullIfEmpty(val('curl-destino')),
            placa: nullIfEmpty(val('curl-placa')),
            transportista: nullIfEmpty(val('curl-transportista')),
            fecha_prevista: nullIfEmpty(val('curl-fecha')),
            observaciones: nullIfEmpty(val('curl-observaciones')),
        };

        Object.entries(fields).forEach(([k, v]) => {
            if (v !== null) payload[k] = v;
        });

        // Mover lineas al final para mejor legibilidad
        delete payload.lineas;
        payload.lineas =
            lineas.length > 0 ? lineas : [{ nombre: '<nombre_producto>', cantidad: 1 }];

        return payload;
    }

    /** Genera el string cURL completo. */
    function generateCurl() {
        const url = `${BASE_URL}/api/inventario/transportes`;
        const token = API_TOKEN || '<BEARER_TOKEN>';
        const tenant = TENANT_ID || '<TENANT_ID>';
        const body = JSON.stringify(buildPayload(), null, 2);

        return [
            `curl -X POST \\`,
            `  "${url}" \\`,
            `  -H "Authorization: Bearer ${token}" \\`,
            `  -H "X-Tenant-Id: ${tenant}" \\`,
            `  -H "Content-Type: application/json" \\`,
            `  -d '${body}'`,
        ].join('\n');
    }

    // ── Actualizar preview ───────────────────────────────────────────────────

    function updatePreview() {
        const code = document.querySelector('#curl-preview code');
        if (code) code.textContent = generateCurl();
    }

    // ── Gestión de líneas de producto ────────────────────────────────────────

    function addLine(data = {}) {
        const container = document.getElementById('curl-lines-container');
        if (!container) return;

        const row = document.createElement('div');
        row.className = 'curl-line-row';
        row.innerHTML = `
            <input type="text"   data-line-ref      placeholder="SKU-001"         value="${escHtml(data.referencia_producto ?? '')}" autocomplete="off" />
            <input type="text"   data-line-nombre   placeholder="Nombre *"        value="${escHtml(data.nombre ?? '')}"              autocomplete="off" />
            <input type="number" data-line-cantidad placeholder="Cantidad"        value="${data.cantidad ?? ''}"                     min="0.0001" step="any" />
            <input type="text"   data-line-unidad   placeholder="piezas / kg …"  value="${escHtml(data.unidad ?? '')}"              autocomplete="off" />
            <button class="curl-remove-line" type="button" title="Eliminar línea">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        `;

        row.querySelector('.curl-remove-line').addEventListener('click', () => {
            row.remove();
            updatePreview();
        });

        row.querySelectorAll('input').forEach(input =>
            input.addEventListener('input', updatePreview)
        );

        container.appendChild(row);
    }

    function escHtml(str) {
        return str.replace(/&/g, '&amp;').replace(/"/g, '&quot;');
    }

    // ── Cargar plantilla ─────────────────────────────────────────────────────

    function loadTemplate(name) {
        const tpl = TEMPLATES[name];
        if (!tpl) return;

        document.getElementById('curl-referencia').value = tpl.referencia ?? '';
        document.getElementById('curl-proveedor').value = tpl.proveedor ?? '';
        document.getElementById('curl-origen').value = tpl.origen ?? '';
        document.getElementById('curl-destino').value = tpl.destino ?? '';
        document.getElementById('curl-placa').value = tpl.placa ?? '';
        document.getElementById('curl-transportista').value = tpl.transportista ?? '';
        document.getElementById('curl-fecha').value = tpl.fecha_prevista ?? '';
        document.getElementById('curl-observaciones').value = tpl.observaciones ?? '';

        const container = document.getElementById('curl-lines-container');
        if (container) {
            container.innerHTML = '';
            tpl.lineas.forEach(line => addLine(line));
        }

        updatePreview();
    }

    function clearForm() {
        [
            'curl-referencia',
            'curl-proveedor',
            'curl-origen',
            'curl-destino',
            'curl-placa',
            'curl-transportista',
            'curl-fecha',
            'curl-observaciones',
        ].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        const container = document.getElementById('curl-lines-container');
        if (container) container.innerHTML = '';
        addLine();
        updatePreview();
    }

    // ── Copiar al portapapeles ───────────────────────────────────────────────

    function setupCopyButton() {
        const btn = document.getElementById('curl-copy-btn');
        if (!btn) return;

        btn.addEventListener('click', () => {
            const curl = document.querySelector('#curl-preview code')?.textContent ?? '';
            if (!curl) return;

            navigator.clipboard
                .writeText(curl)
                .then(() => {
                    btn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Copiado!
                `;
                    btn.classList.add('copied');
                    setTimeout(() => {
                        btn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                        Copiar
                    `;
                        btn.classList.remove('copied');
                    }, 2000);
                })
                .catch(() => {
                    // Fallback para navegadores sin clipboard API
                    const ta = document.createElement('textarea');
                    ta.value = curl;
                    ta.style.position = 'fixed';
                    ta.style.opacity = '0';
                    document.body.appendChild(ta);
                    ta.select();
                    document.execCommand('copy');
                    document.body.removeChild(ta);
                });
        });
    }

    // ── Inicialización ───────────────────────────────────────────────────────

    document.addEventListener('DOMContentLoaded', () => {
        // Botones de plantilla
        document.querySelectorAll('[data-curl-template]').forEach(btn => {
            btn.addEventListener('click', () => {
                const tpl = btn.dataset.curlTemplate;
                if (tpl === 'vaciar') clearForm();
                else loadTemplate(tpl);
            });
        });

        // Botón añadir línea
        document.getElementById('curl-add-line')?.addEventListener('click', () => {
            addLine();
            updatePreview();
        });

        // Cambios en campos del formulario
        [
            'curl-referencia',
            'curl-proveedor',
            'curl-origen',
            'curl-destino',
            'curl-placa',
            'curl-transportista',
            'curl-fecha',
            'curl-observaciones',
        ].forEach(id => document.getElementById(id)?.addEventListener('input', updatePreview));

        setupCopyButton();

        // Estado inicial: una línea vacía + preview
        addLine();
        updatePreview();
    });
}
