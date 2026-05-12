<div class="panel-header">
    <h1>Información de la Empresa</h1>
    <p>Detalles técnicos y administrativos de la organización actual en el tenant.</p>
</div>

<div class="info-grid">
    <div class="info-card">
        <h3>Nombre de Empresa</h3>
        <p>{{ optional(tenant())->name ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Identificador del Tenant</h3>
        <p class="cp-company-tenant-id">{{ optional(tenant())->id ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Slug / Ruta</h3>
        <p>{{ optional(tenant())->slug ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Base de Datos</h3>
        <p class="cp-company-monospace">{{ optional(tenant())->db_name ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Dominio de Acceso</h3>
        <p class="cp-company-monospace">{{ request()->getHost() }}</p>
    </div>

    <div class="info-card">
        <h3>Estado Organizacional</h3>
        <p class="cp-company-status-text">
            <span class="cp-company-status-dot"></span>
            {{ optional(tenant())->status ?? 'Activo' }}
        </p>
    </div>
</div>

{{-- ── Generador de cURL para API de Transportes ──────────────────────────────── --}}
<div class="curl-builder"
     data-curl-tenant-id="{{ optional(tenant())->id ?? '' }}"
     data-curl-api-token="{{ $apiToken ?? '' }}"
     data-curl-base-url="{{ rtrim(config('app.url'), '/') }}">

    <div class="curl-builder-header">
        <div>
            <h2>Generador de cURL &mdash; API de Transportes</h2>
            <p>Construye y copia el comando curl para registrar un transporte. El token de autenticación y el Tenant ID se incluyen automáticamente.</p>
        </div>
        <div class="curl-status-badges">
            <span class="curl-badge {{ $apiToken ? 'curl-badge--ok' : 'curl-badge--warn' }}">
                @if($apiToken)
                    <iconify-icon icon="lucide:check" width="13" height="13"></iconify-icon>
                    Token configurado
                @else
                    <iconify-icon icon="lucide:x" width="13" height="13"></iconify-icon>
                    Sin token API
                @endif
            </span>
            <span class="curl-badge curl-badge--info">
                <iconify-icon icon="lucide:building-2" width="13" height="13"></iconify-icon>
                Tenant: {{ optional(tenant())->id ?? 'N/A' }}
            </span>
        </div>
    </div>

    {{-- Preload templates --}}
    <div class="curl-preload-bar">
        <span class="curl-preload-label">Precargar plantilla:</span>
        <button class="curl-tpl-btn" data-curl-template="tornillos">Tornillos</button>
        <button class="curl-tpl-btn" data-curl-template="paletas">Paletas</button>
        <button class="curl-tpl-btn" data-curl-template="quimicos">Químicos</button>
        <button class="curl-tpl-btn curl-tpl-btn--clear" data-curl-template="vaciar">✕ Limpiar</button>
    </div>

    <div class="curl-builder-layout">

        {{-- LEFT: Form --}}
        <div class="curl-form-panel">
            <p class="curl-form-title">Datos del transporte</p>

            <div class="curl-fields-grid">
                <div class="curl-field">
                    <label for="curl-referencia">Referencia</label>
                    <input type="text" id="curl-referencia" placeholder="TR-EXT-001" autocomplete="off" />
                </div>
                <div class="curl-field">
                    <label for="curl-proveedor">Proveedor</label>
                    <input type="text" id="curl-proveedor" placeholder="Empresa Logística S.A." autocomplete="off" />
                </div>
                <div class="curl-field">
                    <label for="curl-origen">Origen</label>
                    <input type="text" id="curl-origen" placeholder="Madrid" autocomplete="off" />
                </div>
                <div class="curl-field">
                    <label for="curl-destino">Destino</label>
                    <input type="text" id="curl-destino" placeholder="Barcelona" autocomplete="off" />
                </div>
                <div class="curl-field">
                    <label for="curl-placa">Matrícula / Placa</label>
                    <input type="text" id="curl-placa" placeholder="1234ABC" autocomplete="off" />
                </div>
                <div class="curl-field">
                    <label for="curl-transportista">Transportista</label>
                    <input type="text" id="curl-transportista" placeholder="Juan García" autocomplete="off" />
                </div>
                <div class="curl-field curl-field--full">
                    <label for="curl-fecha">Fecha prevista</label>
                    <input type="datetime-local" id="curl-fecha" />
                </div>
                <div class="curl-field curl-field--full">
                    <label for="curl-observaciones">Observaciones</label>
                    <input type="text" id="curl-observaciones" placeholder="Ej: Producto frágil" autocomplete="off" />
                </div>
            </div>

            {{-- Product lines --}}
            <div class="curl-lines-section">
                <div class="curl-lines-header">
                    <p class="curl-form-title">Líneas de productos</p>
                    <button id="curl-add-line" type="button">
                        <iconify-icon icon="lucide:plus" width="14" height="14"></iconify-icon>
                        Añadir línea
                    </button>
                </div>
                <div class="curl-lines-labels">
                    <span>Ref. producto</span>
                    <span>Nombre *</span>
                    <span>Cantidad *</span>
                    <span>Unidad</span>
                    <span></span>
                </div>
                <div id="curl-lines-container"></div>
            </div>
        </div>

        {{-- RIGHT: cURL preview --}}
        <div class="curl-preview-panel">
            <div class="curl-preview-header">
                <span>Vista previa del cURL</span>
                <button id="curl-copy-btn" type="button">
                    <iconify-icon icon="lucide:copy" width="14" height="14"></iconify-icon>
                    Copiar
                </button>
            </div>
            <pre id="curl-preview" class="curl-code-block"><code></code></pre>
            @if(!$apiToken)
                <div class="curl-warn-box">
                    <iconify-icon icon="lucide:triangle-alert" width="16" height="16"></iconify-icon>
                    No hay ningún token API activo para este tenant. Crea uno en la sección de API para usar este endpoint.
                </div>
            @endif
        </div>

    </div>
</div>
