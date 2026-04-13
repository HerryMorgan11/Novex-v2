/**
 * Control Panel - Navegación por secciones del panel de control.
 * Muestra/oculta secciones (Dashboard, Usuarios, Empresa, Módulos) al hacer clic en el sidebar.
 */

const navButtons = document.querySelectorAll('[data-panel-section]');
const sections = document.querySelectorAll('[data-panel-content]');

/**
 * Activa la sección indicada y desactiva el resto.
 * @param {string} sectionName - Nombre de la sección a mostrar.
 */
function showSection(sectionName) {
    sections.forEach(section => {
        const isActive = section.dataset.panelContent === sectionName;
        section.style.display = isActive ? '' : 'none';
    });

    navButtons.forEach(btn => {
        const isActive = btn.dataset.panelSection === sectionName;
        btn.classList.toggle('active', isActive);
    });
}

// Registrar listeners de navegación
navButtons.forEach(btn => {
    btn.addEventListener('click', () => showSection(btn.dataset.panelSection));
});

// Mostrar la sección inicial
if (navButtons.length > 0) {
    showSection(navButtons[0].dataset.panelSection);
}

// ── Gestión de módulos ───────────────────────────────────────────────────────

/** Actualiza el checkbox y la etiqueta de estado de un módulo. */
function applyModuleState(key, enabled) {
    const checkbox = document.getElementById(`module-${key}`);
    const statusEl = document.getElementById(`status-${key}`);

    if (checkbox) checkbox.checked = enabled;

    if (statusEl) {
        statusEl.className = `module-status ${enabled ? 'active' : 'inactive'}`;
        const activeSpan = statusEl.querySelector('.status-active');
        const inactiveSpan = statusEl.querySelector('.status-inactive');
        if (activeSpan) activeSpan.style.display = enabled ? '' : 'none';
        if (inactiveSpan) inactiveSpan.style.display = enabled ? 'none' : '';
    }
}

function loadModules() {
    const defaults = { inventory: true };
    const saved = localStorage.getItem('novex_modules');
    return saved ? { ...defaults, ...JSON.parse(saved) } : defaults;
}

function saveModules(modules) {
    localStorage.setItem('novex_modules', JSON.stringify(modules));
    window.dispatchEvent(new CustomEvent('modules-updated', { detail: modules }));
}

// Inicializar estado de checkboxes de módulos
const modules = loadModules();
Object.entries(modules).forEach(([key, enabled]) => applyModuleState(key, enabled));

// Escuchar cambios en los checkboxes
document.querySelectorAll('[data-module-key]').forEach(checkbox => {
    checkbox.addEventListener('change', () => {
        const currentModules = loadModules();
        currentModules[checkbox.dataset.moduleKey] = checkbox.checked;
        saveModules(currentModules);
        applyModuleState(checkbox.dataset.moduleKey, checkbox.checked);
    });
});
