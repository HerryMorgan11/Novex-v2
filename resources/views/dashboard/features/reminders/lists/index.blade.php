@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/reminders/reminders.css'])
@endpush

@section('content')
<div class="lists-page-wrap">

    @if(session('success'))
        <div class="lists-alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="lists-alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="lists-page-header">
        <div class="lists-page-header-left">
            <a href="{{ route('reminders.index') }}" class="lists-back-link">← Recordatorios</a>
            <h1 class="lists-page-title">Mis listas</h1>
        </div>
        <button onclick="openNewListModal()" class="lists-new-btn">
            + Nueva lista
        </button>
    </div>

    @if($lists->isEmpty())
        <div class="lists-empty-state">
            <p class="lists-empty-title">Sin listas</p>
            <p class="lists-empty-text">Crea tu primera lista para organizar tus recordatorios.</p>
            <button onclick="openNewListModal()"
                    class="tbl-action rem-lists-create-btn">
                Crear lista
            </button>
        </div>
    @else
    <div class="table-wrap">
        <table class="lists-table">
            <thead>
                <tr>
                    <th>Lista</th>
                    <th>Pendientes</th>
                    <th>Vencidas</th>
                    <th>Completadas</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lists as $list)
                <tr>
                    <td>
                        <div class="lists-tbl-cell-name">
                            <span class="lists-tbl-dot" style="background:{{ $list->color ?? '#007aff' }};"></span>
                            <span>{{ $list->name }}</span>
                            @if($list->is_default)
                                <span class="tbl-badge rem-lists-badge-default">Por defecto</span>
                            @endif
                        </div>
                    </td>
                    <td class="rem-lists-td-bold">{{ $list->pendingRemindersCount() }}</td>
                    <td>
                        @php $ov = $list->overdueRemindersCount(); @endphp
                        @if($ov > 0)
                            <span class="tbl-badge rem-lists-badge-overdue">{{ $ov }}</span>
                        @else
                            <span class="rem-muted">—</span>
                        @endif
                    </td>
                    <td>{{ $list->completedRemindersCount() }}</td>
                    <td class="rem-muted">{{ $list->totalRemindersCount() }}</td>
                    <td>
                        <div class="lists-tbl-actions">
                            <a href="{{ route('reminders.index', ['list' => $list->id, 'filter' => 'all']) }}"
                               class="tbl-action rem-lists-action-primary">Abrir</a>
                            <a href="{{ route('reminders.lists.edit', $list) }}"
                               class="tbl-action rem-lists-action-secondary">Editar</a>
                            @can('delete', $list)
                            <form action="{{ route('reminders.lists.destroy', $list) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta lista? Los recordatorios se conservarán.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="tbl-action rem-lists-action-danger">Eliminar</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- Modal: Nueva Lista --}}
<div id="modalNewList"
     class="reminder-modal-overlay"
     onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-panel rem-modal-panel-sm">
        <div class="modal-header">
            <h2 class="modal-title">Nueva lista</h2>
            <button onclick="document.getElementById('modalNewList').style.display='none'" class="modal-close-btn">&times;</button>
        </div>
        <form action="{{ route('reminders.lists.store') }}" method="POST">
            @csrf
            @include('dashboard.features.reminders.lists._form')
            <div class="modal-footer">
                <button type="submit" class="btn-primary-lists">Crear lista</button>
                <button type="button" class="modal-cancel-btn"
                        onclick="document.getElementById('modalNewList').style.display='none'">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
function openNewListModal() {
    document.getElementById('modalNewList').style.display = 'flex';
    var nameInput = document.getElementById('list-name-input');
    if (nameInput) nameInput.value = '';
    var defCheck = document.getElementById('is_default');
    if (defCheck) defCheck.checked = false;
    if (typeof selectListColor === 'function') selectListColor('#007aff');
}
</script>

@endsection
