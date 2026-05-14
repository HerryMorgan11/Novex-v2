/* controlPanel/users.js
   Gestión de usuarios del tenant en el panel de control.
   Se carga únicamente en la vista control-panel/ui/users.blade.php. */

/* ─── Helpers ─────────────────────────────────────────────────────────────── */

function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(String(str ?? '')));
    return d.innerHTML;
}

/* ─── Modal: Añadir usuario ────────────────────────────────────────────────── */

function openAddUserModal() {
    const modal = document.getElementById('add-user-modal');
    modal.style.display = 'flex';
    document.getElementById('modal-error').style.display = 'none';
    document.getElementById('password-reveal').style.display = 'none';
    document.getElementById('add-user-form').style.display = 'block';
    document.getElementById('add-user-form').reset();
    const btn = document.getElementById('modal-submit-btn');
    btn.disabled = false;
    btn.textContent = 'Crear usuario';
}

function closeAddUserModal() {
    document.getElementById('add-user-modal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    const addModal = document.getElementById('add-user-modal');
    if (addModal) {
        addModal.addEventListener('click', function (e) {
            if (e.target === this) closeAddUserModal();
        });
    }
});

async function submitAddUser(e) {
    e.preventDefault();
    const btn = document.getElementById('modal-submit-btn');
    const errorDiv = document.getElementById('modal-error');
    btn.disabled = true;
    btn.textContent = 'Creando...';
    errorDiv.style.display = 'none';

    const csrfToken = document.querySelector('#add-user-form [name="_token"]').value;
    const storeUrl = document.getElementById('add-user-form').dataset.storeUrl;

    try {
        const response = await fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                Accept: 'application/json',
            },
            body: JSON.stringify({
                name: document.getElementById('modal-name').value,
                email: document.getElementById('modal-email').value,
                role: document.getElementById('modal-role').value,
            }),
        });

        const data = await response.json();

        if (!response.ok) {
            const msgs = data.errors
                ? Object.values(data.errors).flat().join('<br>')
                : data.message || 'Error al crear el usuario.';
            errorDiv.innerHTML = msgs;
            errorDiv.style.display = 'block';
            btn.disabled = false;
            btn.textContent = 'Crear usuario';
            return;
        }

        document.getElementById('add-user-form').style.display = 'none';
        document.getElementById('provisional-password').textContent = data.plain_password;
        document.getElementById('password-reveal').style.display = 'block';
        appendUserRow(data.user);
    } catch (err) {
        errorDiv.textContent = 'Error de conexión. Inténtalo de nuevo.';
        errorDiv.style.display = 'block';
        btn.disabled = false;
        btn.textContent = 'Crear usuario';
    }
}

function appendUserRow(user) {
    const tbody = document.getElementById('users-tbody');
    const emptyRow = document.getElementById('empty-row');
    if (emptyRow) emptyRow.remove();

    const row = document.createElement('tr');
    row.id = 'user-row-' + user.id;
    row.innerHTML = `
        <td>${escHtml(user.name)}</td>
        <td>${escHtml(user.email)}</td>
        <td><span class="cp-users-badge-role">${escHtml(user.role_label)}</span></td>
        <td><span class="cp-users-badge-status cp-users-status-pending">${escHtml(user.status_label)}</span></td>
        <td>${escHtml(user.created_at)}</td>
        <td>
            <div class="cp-users-actions">
                <button type="button" class="cp-users-btn-delete"
                    onclick="openDeleteUserModal('${escHtml(user.id)}','${escHtml(user.name)}')"
                    title="Eliminar usuario">
                    <iconify-icon icon="lucide:trash-2" width="13" height="13"></iconify-icon>
                </button>
                <select class="cp-users-role-select"
                    onchange="changeUserRole('${escHtml(user.id)}', this.value)"
                    title="Cambiar rol">
                    <option value="admin"    ${user.role === 'admin' ? 'selected' : ''}>Admin</option>
                    <option value="manager"  ${user.role === 'manager' ? 'selected' : ''}>Manager</option>
                    <option value="empleado" ${user.role === 'empleado' ? 'selected' : ''}>Empleado</option>
                </select>
            </div>
        </td>`;
    tbody.appendChild(row);
}

/* ─── Copiar contraseña provisional ───────────────────────────────────────── */

function copyPassword() {
    const pass = document.getElementById('provisional-password').textContent;
    navigator.clipboard.writeText(pass).then(() => {
        const btn = document.getElementById('copy-btn');
        btn.textContent = '✓ Copiada';
        btn.classList.add('cp-copy-btn--copied');
        setTimeout(() => {
            btn.textContent = 'Copiar';
            btn.classList.remove('cp-copy-btn--copied');
        }, 2000);
    });
}

/* ─── Cambiar rol ──────────────────────────────────────────────────────────── */

async function changeUserRole(userId, role) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const res = await fetch(`/controlpanel/users/${userId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json',
        },
        body: JSON.stringify({ role }),
    });
    if (!res.ok) {
        const d = await res.json();
        alert(d.message || 'Error al cambiar el rol.');
    }
}

/* ─── Cambiar estado ───────────────────────────────────────────────────────── */

async function toggleUserStatus(userId, newStatus) {
    const label = newStatus === 'active' ? 'Activo' : 'Deshabilitado';
    if (!confirm(`¿Cambiar el estado a "${label}"?`)) return;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const res = await fetch(`/controlpanel/users/${userId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json',
        },
        body: JSON.stringify({ status: newStatus }),
    });
    if (res.ok) {
        location.reload();
    } else {
        const d = await res.json();
        alert(d.message || 'Error.');
    }
}

/* ─── Modal: Eliminar usuario ──────────────────────────────────────────────── */

let _deleteUserId = null;

function openDeleteUserModal(userId, name) {
    _deleteUserId = userId;
    document.getElementById('delete-user-name').textContent = name;
    document.getElementById('delete-user-modal').style.display = 'flex';
}

function closeDeleteUserModal() {
    document.getElementById('delete-user-modal').style.display = 'none';
    _deleteUserId = null;
}

async function confirmDeleteUser() {
    if (!_deleteUserId) return;
    const userId = _deleteUserId;
    const btn = document.getElementById('delete-user-confirm-btn');
    btn.disabled = true;
    btn.textContent = 'Eliminando...';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const res = await fetch(`/controlpanel/users/${userId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json',
        },
    });
    btn.disabled = false;
    btn.textContent = 'Eliminar';
    if (res.ok) {
        closeDeleteUserModal();
        document.getElementById('user-row-' + userId)?.remove();
    } else {
        const d = await res.json();
        closeDeleteUserModal();
        alert(d.message || 'Error al eliminar.');
    }
}

// La vista usa handlers inline (`onclick`, `onsubmit`, `onchange`), pero este
// archivo se carga como módulo ES con Vite. Exponemos explícitamente las
// funciones necesarias en `window` para que esos handlers puedan resolverlas.
window.openAddUserModal = openAddUserModal;
window.closeAddUserModal = closeAddUserModal;
window.submitAddUser = submitAddUser;
window.copyPassword = copyPassword;
window.changeUserRole = changeUserRole;
window.toggleUserStatus = toggleUserStatus;
window.openDeleteUserModal = openDeleteUserModal;
window.closeDeleteUserModal = closeDeleteUserModal;
window.confirmDeleteUser = confirmDeleteUser;
