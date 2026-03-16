<div class="panel-header">
    <h1>Gestión de Usuarios</h1>
    <p>Administra los roles, permisos y el estado de todos los usuarios registrados en el tenant actual.</p>
</div>

<div class="table-container">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Fecha de Registro</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->status }}</td>
                        <td>{{ $user->created_at }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">No se encontraron usuarios.</td>
                    </tr>
                @endforelse
                
            </tbody>
        </table>
    </div>
</div>
