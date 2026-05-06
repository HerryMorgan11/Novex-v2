{{-- Control Panel - Gestión de Usuarios
     Muestra la tabla de usuarios del tenant con rol, estado y acciones.
     El botón "Añadir usuario" abre un modal que hace POST /controlpanel/users via fetch.
     Solo los Admin ven los controles de creación/edición/eliminación. --}}

@php
    $isAdmin = auth()->user()?->isAdminInCurrentTenant() ?? false;
@endphp

<div class="panel-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
    <div>
        <h1>Gestión de Usuarios</h1>
        <p>Administra los roles, permisos y el estado de todos los usuarios del tenant.</p>
    </div>
    @if ($isAdmin)
        <button type="button" onclick="openAddUserModal()"
            style="display:flex; align-items:center; gap:0.5rem; background:#6366f1; color:#fff; border:none; border-radius:8px; padding:0.6rem 1.2rem; font-size:0.875rem; font-weight:600; cursor:pointer; transition:background 0.2s;"
            onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Añadir usuario
        </button>
    @endif
</div>

<div class="table-container">
    <div class="table-wrapper">
        <table id="users-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha de Registro</th>
                    @if ($isAdmin)
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody id="users-tbody">
                @forelse ($users as $user)
                    <tr id="user-row-{{ $user->id }}">
                        <td>
                            {{ $user->name }}
                            @if ($user->is_owner ?? false)
                                <span style="font-size:0.7rem; background:#fef3c7; color:#b45309; border-radius:4px; padding:1px 6px; margin-left:4px; font-weight:600;">Propietario</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span style="font-size:0.8rem; background:#ede9fe; color:#6d28d9; border-radius:4px; padding:2px 8px; font-weight:500;">
                                {{ $user->role ?? '—' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusStyle = match($user->raw_status ?? '') {
                                    'active'            => 'background:#dcfce7; color:#15803d;',
                                    'pending','invited' => 'background:#fef9c3; color:#a16207;',
                                    'disabled'          => 'background:#fee2e2; color:#dc2626;',
                                    default             => 'background:#f1f5f9; color:#64748b;',
                                };
                            @endphp
                            <span style="font-size:0.8rem; border-radius:4px; padding:2px 8px; font-weight:500; {{ $statusStyle }}">
                                {{ $user->status ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}</td>
                        @if ($isAdmin)
                            <td>
                                <div style="display:flex; gap:0.5rem; flex-wrap:wrap; align-items:center;">
                                    @if (!($user->is_owner ?? false))
                                        <select onchange="changeUserRole('{{ $user->id }}', this.value)"
                                            style="font-size:0.75rem; padding:3px 6px; border-radius:6px; border:1px solid #cbd5e1; color:#475569; cursor:pointer;" title="Cambiar rol">
                                            <option value="admin"    {{ ($user->raw_role ?? '') === 'admin'    ? 'selected' : '' }}>Admin</option>
                                            <option value="manager"  {{ ($user->raw_role ?? '') === 'manager'  ? 'selected' : '' }}>Manager</option>
                                            <option value="empleado" {{ ($user->raw_role ?? '') === 'empleado' ? 'selected' : '' }}>Empleado</option>
                                        </select>
                                        @if (($user->raw_status ?? '') === 'disabled')
                                            <button type="button" onclick="toggleUserStatus('{{ $user->id }}', 'active')"
                                                style="font-size:0.75rem; padding:3px 8px; border-radius:6px; border:1px solid #86efac; background:#dcfce7; color:#15803d; cursor:pointer;">Activar</button>
                                        @elseif (($user->raw_status ?? '') === 'active')
                                            <button type="button" onclick="toggleUserStatus('{{ $user->id }}', 'disabled')"
                                                style="font-size:0.75rem; padding:3px 8px; border-radius:6px; border:1px solid #fca5a5; background:#fee2e2; color:#dc2626; cursor:pointer;">Deshabilitar</button>
                                        @endif
                                        <button type="button" onclick="deleteUser('{{ $user->id }}', '{{ addslashes($user->name) }}')"
                                            style="font-size:0.75rem; padding:3px 8px; border-radius:6px; border:1px solid #fca5a5; background:#fff; color:#dc2626; cursor:pointer;" title="Eliminar usuario">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                        </button>
                                    @else
                                        <span style="font-size:0.75rem; color:#94a3b8;">—</span>
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr id="empty-row">
                        <td colspan="{{ $isAdmin ? 6 : 5 }}" class="cp-users-empty-cell">No se encontraron usuarios.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($isAdmin)
{{-- ──────────────────── MODAL AÑADIR USUARIO ──────────────────── --}}
<div id="add-user-modal"
    style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.5); backdrop-filter:blur(2px); align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; padding:2rem; width:100%; max-width:440px; margin:1rem; box-shadow:0 20px 60px rgba(0,0,0,0.25); position:relative;">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h3 style="font-size:1.125rem; font-weight:700; color:#1e293b; margin:0;">Añadir usuario</h3>
            <button type="button" onclick="closeAddUserModal()"
                style="background:none; border:none; cursor:pointer; color:#94a3b8; padding:4px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div id="modal-error" style="display:none; background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:0.75rem 1rem; margin-bottom:1rem; font-size:0.875rem; color:#dc2626;"></div>

        <form id="add-user-form" onsubmit="submitAddUser(event)">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.8rem; font-weight:600; color:#475569; margin-bottom:0.4rem;">NOMBRE COMPLETO</label>
                <input type="text" id="modal-name" name="name" required placeholder="Ej: Ana García"
                    style="width:100%; padding:0.6rem 0.75rem; border:1px solid #cbd5e1; border-radius:8px; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
            </div>
            <div style="margin-bottom:1rem;">
                <label style="display:block; font-size:0.8rem; font-weight:600; color:#475569; margin-bottom:0.4rem;">EMAIL</label>
                <input type="email" id="modal-email" name="email" required placeholder="ana@empresa.com"
                    style="width:100%; padding:0.6rem 0.75rem; border:1px solid #cbd5e1; border-radius:8px; font-size:0.9rem; color:#1e293b; outline:none; box-sizing:border-box;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
            </div>
            <div style="margin-bottom:1.5rem;">
                <label style="display:block; font-size:0.8rem; font-weight:600; color:#475569; margin-bottom:0.4rem;">ROL</label>
                <select id="modal-role" name="role" required
                    style="width:100%; padding:0.6rem 0.75rem; border:1px solid #cbd5e1; border-radius:8px; font-size:0.9rem; color:#1e293b; background:#fff; outline:none; cursor:pointer; box-sizing:border-box;"
                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                    <option value="" disabled selected>Selecciona un rol</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="empleado">Empleado</option>
                </select>
            </div>
            <button type="submit" id="modal-submit-btn"
                style="width:100%; background:#6366f1; color:#fff; border:none; border-radius:8px; padding:0.75rem; font-size:0.9rem; font-weight:600; cursor:pointer; transition:background 0.2s;"
                onmouseover="this.style.background='#4f46e5'" onmouseout="this.style.background='#6366f1'">
                Crear usuario
            </button>
        </form>

        {{-- Panel de contraseña provisional --}}
        <div id="password-reveal" style="display:none; margin-top:1.5rem; background:#f0fdf4; border:1px solid #86efac; border-radius:10px; padding:1rem 1.25rem;">
            <p style="font-size:0.8rem; color:#15803d; font-weight:600; margin:0 0 0.5rem;">✓ Usuario creado correctamente</p>
            <p style="font-size:0.8rem; color:#475569; margin:0 0 0.75rem;">Copia esta contraseña provisional y entrégasela al usuario. No se volverá a mostrar.</p>
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <code id="provisional-password"
                    style="flex:1; background:#fff; border:1px solid #86efac; border-radius:6px; padding:0.5rem 0.75rem; font-size:1rem; font-weight:700; color:#1e293b; letter-spacing:0.05em;"></code>
                <button type="button" onclick="copyPassword()" id="copy-btn"
                    style="background:#15803d; color:#fff; border:none; border-radius:6px; padding:0.5rem 0.75rem; font-size:0.8rem; font-weight:600; cursor:pointer; white-space:nowrap;">
                    Copiar
                </button>
            </div>
            <button type="button" onclick="closeAddUserModal()"
                style="margin-top:1rem; width:100%; background:transparent; border:1px solid #cbd5e1; border-radius:8px; padding:0.5rem; font-size:0.875rem; color:#475569; cursor:pointer;">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
function openAddUserModal() {
    document.getElementById('add-user-modal').style.display = 'flex';
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

document.getElementById('add-user-modal').addEventListener('click', function(e) {
    if (e.target === this) closeAddUserModal();
});

async function submitAddUser(e) {
    e.preventDefault();
    const btn = document.getElementById('modal-submit-btn');
    const errorDiv = document.getElementById('modal-error');
    btn.disabled = true;
    btn.textContent = 'Creando...';
    errorDiv.style.display = 'none';

    const csrfToken = document.querySelector('#add-user-form [name="_token"]').value;

    try {
        const response = await fetch('{{ route("controlpanel.users.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                name:  document.getElementById('modal-name').value,
                email: document.getElementById('modal-email').value,
                role:  document.getElementById('modal-role').value,
            }),
        });

        const data = await response.json();

        if (!response.ok) {
            const msgs = data.errors
                ? Object.values(data.errors).flat().join('<br>')
                : (data.message || 'Error al crear el usuario.');
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
        <td><span style="font-size:0.8rem; background:#ede9fe; color:#6d28d9; border-radius:4px; padding:2px 8px; font-weight:500;">${escHtml(user.role_label)}</span></td>
        <td><span style="font-size:0.8rem; border-radius:4px; padding:2px 8px; font-weight:500; background:#fef9c3; color:#a16207;">${escHtml(user.status_label)}</span></td>
        <td>${escHtml(user.created_at)}</td>
        <td>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap; align-items:center;">
                <select onchange="changeUserRole('${escHtml(user.id)}', this.value)"
                    style="font-size:0.75rem; padding:3px 6px; border-radius:6px; border:1px solid #cbd5e1; color:#475569; cursor:pointer;">
                    <option value="admin"    ${user.role==='admin'    ?'selected':''}>Admin</option>
                    <option value="manager"  ${user.role==='manager'  ?'selected':''}>Manager</option>
                    <option value="empleado" ${user.role==='empleado' ?'selected':''}>Empleado</option>
                </select>
                <button type="button" onclick="deleteUser('${escHtml(user.id)}','${escHtml(user.name)}')"
                    style="font-size:0.75rem; padding:3px 8px; border-radius:6px; border:1px solid #fca5a5; background:#fff; color:#dc2626; cursor:pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                </button>
            </div>
        </td>`;
    tbody.appendChild(row);
}

function copyPassword() {
    const pass = document.getElementById('provisional-password').textContent;
    navigator.clipboard.writeText(pass).then(() => {
        const btn = document.getElementById('copy-btn');
        btn.textContent = '✓ Copiada';
        btn.style.background = '#166534';
        setTimeout(() => { btn.textContent = 'Copiar'; btn.style.background = '#15803d'; }, 2000);
    });
}

async function changeUserRole(userId, role) {
    const csrfToken = '{{ csrf_token() }}';
    const res = await fetch(`/controlpanel/users/${userId}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ role }),
    });
    if (!res.ok) { const d = await res.json(); alert(d.message || 'Error al cambiar el rol.'); }
}

async function toggleUserStatus(userId, newStatus) {
    if (!confirm(`¿Cambiar el estado a "${newStatus === 'active' ? 'Activo' : 'Deshabilitado'}"?`)) return;
    const csrfToken = '{{ csrf_token() }}';
    const res = await fetch(`/controlpanel/users/${userId}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ status: newStatus }),
    });
    if (res.ok) { location.reload(); } else { const d = await res.json(); alert(d.message || 'Error.'); }
}

async function deleteUser(userId, name) {
    if (!confirm(`¿Eliminar a "${name}" del tenant? Esta acción no se puede deshacer.`)) return;
    const csrfToken = '{{ csrf_token() }}';
    const res = await fetch(`/controlpanel/users/${userId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
    });
    if (res.ok) { document.getElementById('user-row-' + userId)?.remove(); }
    else { const d = await res.json(); alert(d.message || 'Error al eliminar.'); }
}

function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(String(str ?? '')));
    return d.innerHTML;
}
</script>
@endif
