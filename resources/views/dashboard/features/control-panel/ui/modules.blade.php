<div class="panel-header">
    <h1>Módulos Disponibles</h1>
    <p>Activa o desactiva los módulos que necesitas. Los módulos activos aparecerán en tu menú lateral.</p>
</div>

<div x-data="moduleManager()" class="modules-grid">
    <!-- Inventario Module -->
    <div class="module-card">
        <div class="module-card-header">
            <div>
                <div class="module-icon">
                    <iconify-icon icon="streamline-ultimate:warehouse-cart-packages-2-bold"></iconify-icon>
                </div>
                <h3 class="module-name">Inventario</h3>
            </div>
            <label class="switch">
                <input 
                    type="checkbox" 
                    x-model="modules.inventory"
                    @change="saveModules()"
                >
                <span class="slider"></span>
            </label>
        </div>
        <p class="module-description">Gestiona tu inventario, variantes y todo el stock de tus productos de forma unificada.</p>
        <div class="module-status" :class="modules.inventory ? 'active' : 'inactive'">
            <span x-show="modules.inventory">✓ Activo</span>
            <span x-show="!modules.inventory">Inactivo</span>
        </div>
    </div>

    <!-- Contabilidad Module -->
    <div class="module-card">
        <div class="module-card-header">
            <div>
                <div class="module-icon">
                    <iconify-icon icon="material-symbols:finance-rounded"></iconify-icon>
                </div>
                <h3 class="module-name">Contabilidad</h3>
            </div>
            <label class="switch disabled">
                <input 
                    type="checkbox" 
                    disabled
                >
                <span class="slider"></span>
            </label>
        </div>
        <p class="module-description">Genera facturas, controla tus ingresos e impuestos contables mensuales automáticamente.</p>
        <div class="module-status developing">
            <span><iconify-icon icon="tabler:tool"></iconify-icon> En desarrollo</span>
        </div>
    </div>

    <!-- Recursos Humanos Module -->
    <div class="module-card">
        <div class="module-card-header">
            <div>
                <div class="module-icon">
                    <iconify-icon icon="formkit:people"></iconify-icon>
                </div>
                <h3 class="module-name">Recursos Humanos</h3>
            </div>
            <label class="switch disabled">
                <input 
                    type="checkbox" 
                    disabled
                >
                <span class="slider"></span>
            </label>
        </div>
        <p class="module-description">Gestiona tu equipo, nómina, evaluaciones y todo lo relacionado con recursos humanos.</p>
        <div class="module-status developing">
            <span><iconify-icon icon="tabler:tool"></iconify-icon> En desarrollo</span>
        </div>
    </div>
</div>

<script>
    function moduleManager() {
        return {
            modules: {
                inventory: true,
                accounting: false,
                hr: false
            },
            init() {
                this.loadModules();
            },
            loadModules() {
                const saved = localStorage.getItem('novex_modules');
                if (saved) {
                    this.modules = JSON.parse(saved);
                }
            },
            saveModules() {
                localStorage.setItem('novex_modules', JSON.stringify(this.modules));
                // Dispatch custom event para que el sidebar se actualice
                window.dispatchEvent(new CustomEvent('modules-updated', { detail: this.modules }));
            }
        }
    }
</script>
