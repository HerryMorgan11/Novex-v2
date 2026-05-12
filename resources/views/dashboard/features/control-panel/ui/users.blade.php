{{-- Control Panel - Gestión de Usuarios
     Muestra la tabla de usuarios del tenant con rol, estado y acciones.
     El botón "Añadir usuario" abre un modal que hace POST /controlpanel/users via fetch.
     Solo los Admin ven los controles de creación/edición/eliminación. --}}

@php
    $isAdmin = auth()->user()?->isAdminInCurrentTenant() ?? false;
@endphp

<div class="panel-header cp-users-header">
    <div>
        <h1>Gestión de Usuarios</h1>
        <p>Administra los roles, permisos y el estado de todos los usuarios del tenant.</p>
    </div>
    @if ($isAdmin)
        <button type="button" class="cp-users-add-btn" onclick="openAddUserModal()">
            <iconify-icon icon="lucide:plus" width="16" height="16"></iconify-icon>
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
                    @php
                        $statusClass = match($user->raw_status ?? '') {
                            'active'            => 'cp-users-status-active',
                            'pending','invited' => 'cp-users-status-pending',
                            'disabled'          => 'cp-users-status-disabled',
                            default             => 'cp-users-status-default',
                        };
                    @endphp
                    <tr id="user-row-{{ $user->id }}">
                        <td>
                            {{ $user->name }}
                            @if ($user->is_owner ?? false)
                                <span class="cp-users-badge-owner">Propietario</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="cp-users-badge-role">
                                {{ $user->role ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="cp-users-badge-status {{ $statusClass }}">
                                {{ $user->status ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}</td>
                        @if ($isAdmin)
                            <td>
                                <div class="cp-users-actions">
                                    @if (!($user->is_owner ?? false))
                                        @if (($user->raw_status ?? '') === 'disabled')
                                            <button type="button" class="cp-users-btn-activate"
                                                onclick="toggleUserStatus('{{ $user->id }}', 'active')">
                                                Activar
                                            </button>
                                        @elseif (($user->raw_status ?? '') === 'active')
                                            <button type="button" class="cp-users-btn-disable"
                                                onclick="toggleUserStatus('{{ $user->id }}', 'disabled')">
                                                Deshabilitar
                                            </button>
                                        @endif
                                        <button type="button" class="cp-users-btn-delete"
                                            onclick="openDeleteUserModal('{{ $user->id }}', '{{ addslashes($user->name) }}')"
                                            title="Eliminar usuario">
                                            <iconify-icon icon="lucide:trash-2" width="13" height="13"></iconify-icon>
                                        </button>
                                        <select class="cp-users-role-select"
                                            onchange="changeUserRole('{{ $user->id }}', this.value)"
                                            title="Cambiar rol">
                                            <option value="admin"    {{ ($user->raw_role ?? '') === 'admin'    ? 'selected' : '' }}>Admin</option>
                                            <option value="manager"  {{ ($user->raw_role ?? '') === 'manager'  ? 'selected' : '' }}>Manager</option>
                                            <option value="empleado" {{ ($user->raw_role ?? '') === 'empleado' ? 'selected' : '' }}>Empleado</option>
                                        </select>
                                    @else
                                        <span class="cp-users-owner-dash">—</span>
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
{{-- ──────────────────── MODAL CONFIRMAR ELIMINAR USUARIO ──────────────────── --}}
<div id="delete-user-modal" class="cp-modal-overlay"
    onclick="if(event.target===this)closeDeleteUserModal()">
    <div class="cp-modal-box">
        <div class="cp-modal-icon-circle cp-modal-icon-circle--danger">
            <iconify-icon icon="lucide:alert-triangle" width="20" height="20"></iconify-icon>
        </div>
        <h3 class="cp-modal-title">Eliminar usuario</h3>
        <p class="cp-modal-desc">
            ¿Estás seguro de eliminar a <strong id="delete-user-name"></strong>?
            Esta acción no se puede deshacer.
        </p>
        <div class="cp-modal-footer-btns">
            <button type="button" class="cp-modal-cancel-btn" onclick="closeDeleteUserModal()">
                Cancelar
            </button>
            <button type="button" id="delete-user-confirm-btn" class="cp-modal-danger-btn"
                onclick="confirmDeleteUser()">
                Eliminar
            </button>
        </div>
    </div>
</div>

{{-- ──────────────────── MODAL AÑADIR USUARIO ──────────────────── --}}
<div id="add-user-modal" class="cp-modal-overlay">
    <div class="cp-modal-box cp-modal-box--form">

        <div class="cp-modal-header">
            <h3 class="cp-modal-header-title">Añadir usuario</h3>
            <button type="button" class="cp-modal-close-btn" onclick="closeAddUserModal()">
                <iconify-icon icon="lucide:x" width="20" height="20"></iconify-icon>
            </button>
        </div>

        <div id="modal-error" class="cp-modal-error"></div>

        <form id="add-user-form" onsubmit="submitAddUser(event)"
            data-store-url="{{ route('controlpanel.users.store') }}">
            @csrf
            <div class="cp-form-group">
                <label class="cp-form-label" for="modal-name">NOMBRE COMPLETO</label>
                <input type="text" id="modal-name" name="name" required
                    placeholder="Ej: Ana García"
                    class="cp-form-input">
            </div>
            <div class="cp-form-group">
                <label class="cp-form-label" for="modal-email">EMAIL</label>
                <input type="email" id="modal-email" name="email" required
                    placeholder="ana@empresa.com"
                    class="cp-form-input">
            </div>
            <div class="cp-form-group">
                <label class="cp-form-label" for="modal-role">ROL</label>
                <select id="modal-role" name="role" required class="cp-form-select">
                    <option value="" disabled selected>Selecciona un rol</option>
                    <option value="admin">Admin</option>
                    <option value="manager">Manager</option>
                    <option value="empleado">Empleado</option>
                </select>
            </div>
            <button type="submit" id="modal-submit-btn" class="cp-modal-submit-btn">
                Crear usuario
            </button>
        </form>

        {{-- Panel de contraseña provisional --}}
        <div id="password-reveal" class="cp-password-reveal">
            <p class="cp-password-reveal-title">✓ Usuario creado correctamente</p>
            <p class="cp-password-reveal-desc">
                Copia esta contraseña provisional y entrégasela al usuario.
                No se volverá a mostrar.
            </p>
            <div class="cp-password-row">
                <code id="provisional-password" class="cp-password-code"></code>
                <button type="button" id="copy-btn" class="cp-copy-btn" onclick="copyPassword()">
                    Copiar
                </button>
            </div>
            <button type="button" class="cp-password-close-btn" onclick="closeAddUserModal()">
                Cerrar
            </button>
        </div>
    </div>
</div>

@vite('resources/js/controlPanel/users.js')
@endif
