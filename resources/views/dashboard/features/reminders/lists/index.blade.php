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
        background: #007aff;
        color: #fff;
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
</style>
<div style="max-width:700px; margin:32px auto; padding:0 20px;">

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
            <a href="{{ route('reminders.index') }}" style="color:#007aff; font-size:14px; text-decoration:none;">← Recordatorios</a>
            <h1 style="font-size:24px; font-weight:700; color:#1c1c1e; margin:0;">Mis listas</h1>
        </div>
        <button onclick="openNewListModal()"
                style="background:#007aff; color:#fff; padding:10px 18px; border-radius:10px; border:none; cursor:pointer; font-size:14px; font-weight:600;">
            + Nueva lista
        </button>
    </div>

    @if($lists->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#8e8e93;">
            <p style="font-size:18px; font-weight:600; color:#1c1c1e; margin-bottom:8px;">Sin listas</p>
            <p style="font-size:14px; margin-bottom:20px;">Crea tu primera lista para organizar tus recordatorios.</p>
            <button onclick="openNewListModal()"
                    style="background:#007aff; color:#fff; padding:10px 20px; border-radius:10px; border:none; cursor:pointer; font-size:14px; font-weight:600;">
                Crear lista
            </button>
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:8px;">
            @foreach($lists as $list)
                <div style="background:#fff; border:1px solid #e5e5ea; border-radius:12px; padding:14px 18px; display:flex; align-items:center; gap:12px;">
                    <span style="width:14px; height:14px; border-radius:50%; background:{{ $list->color ?? '#007aff' }}; flex-shrink:0;"></span>

                    <div style="flex:1;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="font-size:15px; font-weight:600; color:#1c1c1e;">{{ $list->name }}</span>
                            @if($list->is_default)
                                <span style="background:#e5e5ea; color:#8e8e93; padding:2px 8px; border-radius:10px; font-size:11px;">Por defecto</span>
                            @endif
                        </div>
                        <div style="font-size:12px; color:#8e8e93; margin-top:2px;">
                            {{ $list->pendingRemindersCount() }} pendiente(s) · {{ $list->completedRemindersCount() }} completado(s)
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:8px;">
                        <a href="{{ route('reminders.index', ['list' => $list->id]) }}"
                           style="background:#007aff; color:#fff; padding:7px 14px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:600;">Abrir</a>
                        <a href="{{ route('reminders.lists.edit', $list) }}"
                           style="background:#f2f2f7; color:#1c1c1e; padding:7px 12px; border-radius:8px; text-decoration:none; font-size:13px;">Editar</a>
                        @can('delete', $list)
                            <form action="{{ route('reminders.lists.destroy', $list) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta lista? Los recordatorios se conservarán.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="background:#fee2e2; color:#dc2626; padding:7px 12px; border-radius:8px; border:none; cursor:pointer; font-size:13px;">Eliminar</button>
                            </form>
                        @endcan
                    </div>
                </div>
            @endforeach
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
