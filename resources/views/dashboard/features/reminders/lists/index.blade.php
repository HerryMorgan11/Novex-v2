@extends('dashboard.app.dashboard')

@section('content')
<style>
    .modal-panel {
        background: var(--card, #fff);
        border-radius: 16px;
        padding: 28px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
    }
    .dark-theme .modal-panel {
        background: var(--card);
        border: 1px solid var(--border);
    }
    .modal-panel h2 { color: var(--fg, #1c1c1e); }
    .modal-close-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 22px;
        color: var(--muted, #8e8e93);
        line-height: 1;
    }
    .btn-primary-lists {
        flex: 1;
        background: var(--accent, #007aff);
        color: var(--accent-fg, #fff);
        padding: 12px;
        border-radius: 10px;
        border: none;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
    }
    .modal-cancel-btn {
        flex: 1;
        background: var(--surface-2, #f2f2f7);
        color: var(--fg, #1c1c1e);
        padding: 12px;
        border-radius: 10px;
        border: none;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
    }
    /* Table */
    .table-wrap {
        border: 1px solid var(--border, #e5e5ea);
        border-radius: 10px;
        overflow: hidden;
        background: var(--card, #fff);
    }
    .dark-theme .table-wrap {
        background: var(--card);
        border-color: var(--border);
    }
    .lists-table { width: 100%; border-collapse: collapse; }
    .lists-table th {
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--muted, #8e8e93);
        padding: 10px 14px;
        border-bottom: 1px solid var(--border, #e5e5ea);
        white-space: nowrap;
        background: var(--surface-2, #f5f5f7);
    }
    .dark-theme .lists-table th {
        background: var(--surface-2);
        border-color: var(--border);
        color: var(--muted);
    }
    .lists-table td {
        padding: 12px 14px;
        border-bottom: 1px solid var(--border, #e5e5ea);
        font-size: 14px;
        color: var(--fg, #1c1c1e);
        vertical-align: middle;
    }
    .dark-theme .lists-table td {
        border-color: var(--border);
        color: var(--fg);
    }
    .lists-table tbody tr:last-child td { border-bottom: none; }
    .lists-table tbody tr:hover td { background: var(--surface-2, #f9f9f9); }
    .dark-theme .lists-table tbody tr:hover td { background: var(--surface-2); }
    .tbl-badge {
        display: inline-flex;
        align-items: center;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    .tbl-action {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: opacity .15s;
    }
    .tbl-action:hover { opacity: .8; }
</style>
<div style="max-width:900px; margin:32px auto; padding:0 20px;">

    @if(session('success'))
        <div style="background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; padding:12px 16px; border-radius:10px; margin-bottom:16px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:12px 16px; border-radius:10px; margin-bottom:16px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="{{ route('reminders.index') }}" style="color:var(--accent,#007aff); font-size:14px; text-decoration:none;">← Recordatorios</a>
            <h1 style="font-size:24px; font-weight:700; color:var(--fg,#1c1c1e); margin:0;">Mis listas</h1>
        </div>
        <button onclick="openNewListModal()"
                style="background:#007aff; color:#fff; padding:10px 18px; border-radius:10px; border:none; cursor:pointer; font-size:14px; font-weight:600;">
            + Nueva lista
        </button>
    </div>

    @if($lists->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:var(--muted,#8e8e93);">
            <p style="font-size:17px; font-weight:600; color:var(--fg,#1c1c1e); margin-bottom:8px;">Sin listas</p>
            <p style="font-size:14px; margin-bottom:20px;">Crea tu primera lista para organizar tus recordatorios.</p>
            <button onclick="openNewListModal()"
                    class="tbl-action"
                    style="background:var(--accent,#007aff); color:var(--accent-fg,#fff); padding:10px 20px; font-size:14px;">
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
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span style="width:10px; height:10px; border-radius:50%; background:{{ $list->color ?? '#007aff' }}; flex-shrink:0;"></span>
                            <span style="font-weight:600;">{{ $list->name }}</span>
                            @if($list->is_default)
                                <span class="tbl-badge" style="background:var(--surface-2); color:var(--muted); font-size:10px;">Por defecto</span>
                            @endif
                        </div>
                    </td>
                    <td style="font-weight:600;">{{ $list->pendingRemindersCount() }}</td>
                    <td>
                        @php $ov = $list->overdueRemindersCount(); @endphp
                        @if($ov > 0)
                            <span class="tbl-badge" style="background:#fee2e2; color:#dc2626;">{{ $ov }}</span>
                        @else
                            <span style="color:var(--muted);">—</span>
                        @endif
                    </td>
                    <td>{{ $list->completedRemindersCount() }}</td>
                    <td style="color:var(--muted);">{{ $list->totalRemindersCount() }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:6px;">
                            <a href="{{ route('reminders.index', ['list' => $list->id, 'filter' => 'all']) }}"
                               class="tbl-action" style="background:var(--accent,#007aff); color:var(--accent-fg,#fff);">Abrir</a>
                            <a href="{{ route('reminders.lists.edit', $list) }}"
                               class="tbl-action" style="background:var(--surface-2,#f2f2f7); color:var(--fg,#1c1c1e);">Editar</a>
                            @can('delete', $list)
                            <form action="{{ route('reminders.lists.destroy', $list) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta lista? Los recordatorios se conservarán.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="tbl-action"
                                        style="background:var(--surface-2,#f2f2f7); color:#dc2626;">Eliminar</button>
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
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1000; align-items:center; justify-content:center; padding:20px;"
     onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-panel" style="max-width:480px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
            <h2 style="font-size:20px; font-weight:700; margin:0;">Nueva lista</h2>
            <button onclick="document.getElementById('modalNewList').style.display='none'" class="modal-close-btn">&times;</button>
        </div>
        <form action="{{ route('reminders.lists.store') }}" method="POST">
            @csrf
            @include('dashboard.features.reminders.lists._form')
            <div style="display:flex; gap:10px; margin-top:24px; padding-top:20px; border-top:1px solid var(--border, #e5e5ea);">
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
