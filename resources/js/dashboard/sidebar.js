/**
 * Sidebar - Interacciones del sidebar del dashboard.
 * Gestiona: toggle móvil, dropdown de usuario, módulos desde localStorage.
 */

// ── Sidebar mobile toggle ────────────────────────────────────────────────────

const sidebar = document.getElementById('main-sidebar');
const overlay = document.getElementById('sidebar-overlay');
const menuToggle = document.querySelector('[data-sidebar-toggle]');

function openSidebar() {
    sidebar?.classList.add('open');
    overlay?.classList.add('open');
}

function closeSidebar() {
    sidebar?.classList.remove('open');
    overlay?.classList.remove('open');
}

menuToggle?.addEventListener('click', openSidebar);
overlay?.addEventListener('click', closeSidebar);

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeSidebar();
});

// ── User dropdown ────────────────────────────────────────────────────────────

const userBtn = document.getElementById('user-dropdown-btn');
const dropdownPanel = document.getElementById('user-dropdown-panel');

function openDropdown() {
    dropdownPanel.style.display = '';
    userBtn.setAttribute('aria-expanded', 'true');
}

function closeDropdown() {
    dropdownPanel.style.display = 'none';
    userBtn.setAttribute('aria-expanded', 'false');
}

userBtn?.addEventListener('click', e => {
    e.stopPropagation();
    const isOpen = dropdownPanel.style.display !== 'none';
    isOpen ? closeDropdown() : openDropdown();
});

document.addEventListener('click', e => {
    if (!userBtn?.contains(e.target) && !dropdownPanel?.contains(e.target)) {
        closeDropdown();
    }
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && dropdownPanel?.style.display !== 'none') {
        closeDropdown();
        userBtn?.focus();
    }
});

// ── Módulos desde localStorage ───────────────────────────────────────────────

function loadSidebarModules() {
    const defaults = { inventory: true, accounting: true, hr: true };
    const saved = localStorage.getItem('novex_modules');
    const modules = saved ? { ...defaults, ...JSON.parse(saved) } : defaults;

    document.querySelectorAll('[data-module]').forEach(el => {
        const key = el.dataset.module;
        if (key && !modules[key]) {
            el.classList.add('opacity-50');
        } else {
            el.classList.remove('opacity-50');
        }
    });
}

// Recargar módulos cuando el control panel los actualice
window.addEventListener('modules-updated', loadSidebarModules);

loadSidebarModules();
