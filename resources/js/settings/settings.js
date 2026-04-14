/**
 * Settings - Navegación por secciones del panel de ajustes.
 * Muestra/oculta secciones (Perfil, Seguridad) al hacer clic en los botones del sidebar.
 */

const navButtons = document.querySelectorAll('[data-settings-section]');
const sections = document.querySelectorAll('[data-settings-content]');

/**
 * Activa la sección indicada y desactiva el resto.
 * @param {string} sectionName - Nombre de la sección a mostrar.
 */
function showSection(sectionName) {
    sections.forEach(section => {
        const isActive = section.dataset.settingsContent === sectionName;
        section.style.display = isActive ? '' : 'none';
    });

    navButtons.forEach(btn => {
        const isActive = btn.dataset.settingsSection === sectionName;
        btn.classList.toggle('active', isActive);
    });
}

// Registrar listeners de navegación
navButtons.forEach(btn => {
    btn.addEventListener('click', () => showSection(btn.dataset.settingsSection));
});

// Mostrar la sección inicial (primera disponible)
if (navButtons.length > 0) {
    showSection(navButtons[0].dataset.settingsSection);
}
