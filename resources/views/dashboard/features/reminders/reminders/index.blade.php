@extends('dashboard.app.dashboard')

@section('content')
<div class="reminders-container" style="display: flex; gap: 20px; padding: 20px;">
    <!-- Sidebar Reminders -->
    <aside class="reminders-sidebar" style="flex-shrink: 0;">
        <style>
            .reminders-sidebar {
                background: var(--surface-2, #f5f5f5);
                border-radius: 8px;
                padding: 16px;
                border: 1px solid var(--border, #e0e0e0);
                height: calc(100vh - 160px);
                overflow-y: auto;
                width: 240px;
            }
            .dark-theme .reminders-sidebar {
                background: var(--sidebar);
                border-color: var(--border);
            }
            .reminders-sidebar h3 {
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: var(--muted, #999);
                margin-top: 16px;
                margin-bottom: 8px;
                padding: 0 8px;
            }
            .reminder-filters {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                margin-bottom: 16px;
            }
            .filter-card {
                padding: 12px;
                border-radius: 6px;
                background: var(--card, #ffffff);
                border: 1px solid var(--border, #e0e0e0);
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s;
                color: var(--fg, #333);
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                gap: 8px;
            }
            .dark-theme .filter-card {
                background: var(--card);
                border-color: var(--border);
                color: var(--fg);
            }
            .filter-card:hover {
                background: var(--surface-2, #f0f0f0);
                transform: translateY(-2px);
            }
            .dark-theme .filter-card:hover {
                background: var(--surface-2);
            }
            .filter-card-icon {
                font-size: 20px;
                margin-bottom: 4px;
                display: flex;
                justify-content: center;
            }
            .filter-card-label {
                font-size: 11px;
                font-weight: 600;
                color: var(--muted, #555);
            }
            .dark-theme .filter-card-label {
                color: var(--muted);
            }
            .filter-card-count {
                font-size: 16px;
                font-weight: 700;
                color: var(--accent, #007AFF);
                margin-top: 4px;
            }
            .list-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px 8px;
                border-radius: 6px;
                color: var(--fg, #333);
                text-decoration: none;
                transition: all 0.2s;
                font-size: 14px;
                margin-bottom: 4px;
            }
            .dark-theme .list-item {
                color: var(--fg);
            }
            .list-item:hover {
                background: var(--surface-2, #f0f0f0);
            }
            .dark-theme .list-item:hover {
                background: var(--surface-2);
            }
            .list-color {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                flex-shrink: 0;
            }
            .list-count {
                font-size: 12px;
                color: var(--muted, #999);
                font-weight: 600;
            }
        </style>

        <!-- Smart Lists -->
        <div class="reminder-filters">
            <a href="{{ route('reminders.index', ['filter' => 'all']) }}" class="filter-card" title="Todos">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <iconify-icon icon="lucide:layout-list" width="20"></iconify-icon>
                    <div class="filter-card-count">{{ $allCount }}</div>
                </div>
                <div class="filter-card-label">Todos</div>
            </a>
            <a href="{{ route('reminders.index', ['filter' => 'pending']) }}" class="filter-card" title="Pendientes">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <iconify-icon icon="lucide:clock" width="20"></iconify-icon>
                    <div class="filter-card-count">{{ $pendingCount }}</div>
                </div>
                <div class="filter-card-label">Pendientes</div>
            </a>
            <a href="{{ route('reminders.index', ['filter' => 'completed']) }}" class="filter-card" title="Completados">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <iconify-icon icon="lucide:check-circle-2" width="20"></iconify-icon>
                    <div class="filter-card-count">{{ $completedCount }}</div>
                </div>
                <div class="filter-card-label">Completados</div>
            </a>
            <a href="{{ route('reminders.index', ['filter' => 'overdue']) }}" class="filter-card" title="Vencidos">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <iconify-icon icon="lucide:alert-circle" width="20"></iconify-icon>
                    <div class="filter-card-count">{{ $overdueCount }}</div>
                </div>
                <div class="filter-card-label">Vencidos</div>
            </a>
        </div>

        <!-- Custom Lists -->
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:2px;">
            <h3 style="margin:0;">Mis listas</h3>
            <button onclick="openNewListModal()"
                    style="background:none; border:none; cursor:pointer; color:var(--muted, #999); font-size:20px; font-weight:600; line-height:1; padding:4px 6px; border-radius:6px;"
                    title="Nueva lista">+</button>
        </div>
        <div style="space-y: 4px;">
            @forelse($lists as $list)
                <a href="{{ route('reminders.index', ['list' => $list->id, 'filter' => 'all']) }}" class="list-item">
                    <div style="display: flex; align-items: center; gap: 10px; flex: 1;">
                        <div class="list-color" style="background-color: {{ $list->color ?? '#007AFF' }}"></div>
                        <span>{{ $list->name }}</span>
                    </div>
                    <span class="list-count">{{ $list->reminders_count }}</span>
                </a>
            @empty
                <p style="font-size: 12px; color: var(--text-muted, #999); padding: 8px;">Sin listas</p>
            @endforelse
        </div>

        <div style="margin-top:12px; padding-top:12px; border-top:1px solid var(--border,#e0e0e0);">
            <a href="{{ route('reminders.lists.index') }}"
               style="display:flex; align-items:center; gap:6px; font-size:12px; color:var(--muted,#999); text-decoration:none; padding:6px 8px; border-radius:6px; transition:background .15s;"
               onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='transparent'">
                <iconify-icon icon="lucide:settings-2" width="14"></iconify-icon>
                Gestionar listas
            </a>
        </div>

    </aside>

    <!-- Main Content -->
    <main class="reminders-main" style="flex: 1; min-width: 0;">
        <style>
            .reminders-main {
                background: var(--card, #ffffff);
                border-radius: 8px;
                padding: 24px;
                border: 1px solid var(--border, #e0e0e0);
            }
            .dark-theme .reminders-main {
                background: var(--card);
                border-color: var(--border);
            }
            .reminders-header {
                margin-bottom: 24px;
                display: flex;
                align-items: center;
                gap: 16px;
                flex-wrap: wrap;
            }
            .reminders-title {
                font-size: 28px;
                font-weight: 700;
                color: var(--fg, #333);
                margin: 0;
            }
            .reminders-subtitle {
                font-size: 14px;
                color: var(--muted, #999);
            }
            .reminder-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 16px;
                margin-bottom: 12px;
                background: var(--surface, #fafafa);
                border: 1px solid var(--border, #e0e0e0);
                border-radius: 8px;
                transition: all 0.2s;
            }
            .dark-theme .reminder-item {
                background: var(--surface);
                border-color: var(--border);
            }
            .reminder-item:hover {
                background: var(--surface-2, #f5f5f5);
                border-color: var(--accent, #007AFF);
            }
            .dark-theme .reminder-item:hover {
                background: var(--surface-2);
                border-color: var(--accent);
            }
            .reminder-item.completed {
                opacity: 0.6;
            }
            .reminder-checkbox {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                border: 2px solid var(--border, #e0e0e0);
                cursor: pointer;
                flex-shrink: 0;
                margin-top: 2px;
                background: transparent;
            }
            .dark-theme .reminder-checkbox {
                border-color: var(--border);
            }
            .reminder-content {
                flex: 1;
            }
            .reminder-title {
                font-size: 16px;
                font-weight: 600;
                color: var(--fg, #333);
                margin: 0;
                text-decoration: none;
                cursor: pointer;
                transition: color 0.2s;
            }
            .reminder-title:hover {
                color: var(--accent, #007AFF);
            }
            .reminder-item.completed .reminder-title {
                text-decoration: line-through;
                color: var(--muted, #999);
            }
            .reminder-meta {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-top: 8px;
                font-size: 12px;
                color: var(--muted, #999);
                flex-wrap: wrap;
            }
            .reminder-meta-item {
                display: flex;
                align-items: center;
                gap: 4px;
            }
            .reminder-notes {
                font-size: 13px;
                color: var(--muted-2, #666);
                margin-top: 8px;
                line-height: 1.4;
            }
            .reminder-subtasks {
                margin-top: 12px;
                padding-top: 12px;
                border-top: 1px solid var(--border, #e0e0e0);
            }
            .subtask-item {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                color: var(--muted-2, #666);
                margin-bottom: 4px;
            }
            .empty-state {
                text-align: center;
                padding: 40px 20px;
                color: var(--muted, #999);
            }
            .empty-state-title {
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 8px;
                color: var(--fg, #333);
            }
            .btn-primary {
                display: inline-block;
                background: var(--accent, #007AFF);
                color: var(--accent-fg, #fff);
                padding: 10px 20px;
                border-radius: 6px;
                text-decoration: none;
                font-weight: 600;
                font-size: 14px;
                transition: opacity 0.2s;
                border: none;
                cursor: pointer;
            }
            .btn-primary:hover { opacity: 0.85; }
            .priority-high { color: #FF3B30; font-weight: 700; }
            .priority-medium { color: #FF9500; font-weight: 700; }
            /* Modal dark mode */
            .modal-panel {
                background: #fff;
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
            .modal-panel h2 {
                color: var(--fg, #1c1c1e);
            }
            .modal-close-btn {
                background: none;
                border: none;
                cursor: pointer;
                font-size: 22px;
                color: var(--muted, #8e8e93);
                line-height: 1;
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

        <!-- Header -->
        <div class="reminders-header">
            <h1 class="reminders-title">
                @switch($filter)
                    @case('completed')
                        Completados
                    @break
                    @case('overdue')
                        Vencidos
                    @break
                    @case('today')
                        Hoy
                    @break
                    @case('all')
                        Todos
                    @break
                    @default
                        Pendientes
                @endswitch
            </h1>
            @if($currentList)
            <div style="display:flex; align-items:center; gap:6px; font-size:13px; color:var(--muted,#999); width:100%; margin-top:2px;">
                <span style="width:10px; height:10px; border-radius:50%; background:{{ $currentList->color ?? '#007aff' }}; flex-shrink:0;"></span>
                <iconify-icon icon="lucide:list" width="13"></iconify-icon>
                <span>{{ $currentList->name }}</span>
                <span style="color:var(--border,#ddd);">·</span>
                <a href="{{ route('reminders.index') }}" style="color:var(--muted,#999); text-decoration:none; font-size:12px;">Ver todas</a>
            </div>
            @endif
            @if($currentList)
            @php $listId = $currentList->id; $lf = request('filter', 'all'); @endphp
            <div style="display:flex; gap:6px; flex-wrap:wrap; width:100%; padding-bottom:16px; border-bottom:1px solid var(--border,#e0e0e0); margin-bottom:8px;">
                @foreach([
                    ['all',       'Todas',       'lucide:layout-list'],
                    ['pending',   'Pendientes',  'lucide:clock'],
                    ['overdue',   'Vencidas',    'lucide:alert-circle'],
                    ['completed', 'Completadas', 'lucide:check-circle-2'],
                ] as [$val, $label, $icon])
                <a href="{{ route('reminders.index', ['list' => $listId, 'filter' => $val]) }}"
                   style="display:flex; align-items:center; gap:5px; padding:5px 12px; border-radius:6px; font-size:12px; font-weight:600; text-decoration:none;
                          background:{{ $lf === $val ? 'var(--accent,#007aff)' : 'var(--surface-2,#f2f2f7)' }};
                          color:{{ $lf === $val ? 'var(--accent-fg,#fff)' : 'var(--fg,#555)' }};">
                    <iconify-icon icon="{{ $icon }}" width="13"></iconify-icon>
                    {{ $label }}
                    @if($val === 'overdue' && $currentList->overdueRemindersCount() > 0)
                        <span style="background:#ff3b30; color:#fff; border-radius:8px; padding:1px 5px; font-size:10px; margin-left:2px;">{{ $currentList->overdueRemindersCount() }}</span>
                    @endif
                </a>
                @endforeach
            </div>
            @endif
            <span class="reminders-subtitle">{{ now()->format('l, M d') }}</span>
            <button onclick="document.getElementById('modalNewReminder').style.display='flex'"
                    class="btn-primary"
                    style="margin-left:auto;">
                + Agregar recordatorio
            </button>
        </div>

        <!-- Reminders List -->
        @forelse($reminders as $reminder)
            <form action="{{ route('reminders.complete', $reminder) }}" method="POST" style="display: inline;">
                @csrf
                @method('PATCH')
                <div class="reminder-item {{ $reminder->is_completed ? 'completed' : '' }}">
                    <input 
                        type="checkbox" 
                        class="reminder-checkbox"
                        {{ $reminder->is_completed ? 'checked' : '' }}
                        onchange="this.form.submit()"
                    />
                    <div class="reminder-content">
                        <a href="{{ route('reminders.show', $reminder) }}" class="reminder-title">
                            {{ $reminder->title }}
                            @if($reminder->priority >= 3)
                                <span class="priority-high">!!!</span>
                            @elseif($reminder->priority == 2)
                                <span class="priority-medium">!!</span>
                            @endif
                        </a>

                        @if($reminder->notes)
                            <p class="reminder-notes">{{ Str::limit($reminder->notes, 100) }}</p>
                        @endif

                        <div class="reminder-meta">
                            @if($reminder->due_at)
                                <div class="reminder-meta-item">
                                    <iconify-icon icon="lucide:clock" width="14"></iconify-icon>
                                    <span>{{ $reminder->due_at->format('H:i') }}</span>
                                </div>
                            @endif
                            @if($reminder->list)
                                <div class="reminder-meta-item">
                                    <iconify-icon icon="lucide:list" width="14"></iconify-icon>
                                    <span>{{ $reminder->list->name }}</span>
                                </div>
                            @endif
                        </div>

                        @if($reminder->subtasks->count() > 0)
                            <div class="reminder-subtasks">
                                @foreach($reminder->subtasks->take(3) as $subtask)
                                    <div class="subtask-item">
                                        @if($subtask->is_completed)
                                            <iconify-icon icon="lucide:check" width="14" style="color: var(--primary-color, #007AFF);"></iconify-icon>
                                        @else
                                            <iconify-icon icon="lucide:circle" width="14"></iconify-icon>
                                        @endif
                                        <span class="{{ $subtask->is_completed ? 'line-through text-muted' : '' }}">{{ $subtask->title }}</span>
                                    </div>
                                @endforeach
                                @if($reminder->subtasks->count() > 3)
                                    <div class="subtask-item">
                                        <span>+{{ $reminder->subtasks->count() - 3 }} más</span>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('reminders.show', $reminder) }}" class="btn-primary" style="margin-left: auto; flex-shrink: 0; align-self: center; padding: 8px 12px; font-size: 12px;">
                        Ver
                    </a>
                </div>
            </form>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">
                    <iconify-icon icon="lucide:clipboard-list" width="48"></iconify-icon>
                </div>
                <div class="empty-state-title">Sin recordatorios</div>
                <p style="margin-bottom: 16px;">No hay recordatorios para esta vista</p>
                <a href="{{ route('reminders.create') }}" class="btn-primary">Crear Recordatorio</a>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($reminders->hasPages())
            <div style="margin-top: 24px; text-align: center;">
                {{ $reminders->links() }}
            </div>
        @endif
    </main>
</div>

{{-- Modal: Nuevo Recordatorio --}}
<div id="modalNewReminder"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1000; align-items:center; justify-content:center; padding:20px;"
     onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-panel" style="max-width:600px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
            <h2 style="font-size:20px; font-weight:700; margin:0;">Nuevo recordatorio</h2>
            <button onclick="document.getElementById('modalNewReminder').style.display='none'" class="modal-close-btn">&times;</button>
        </div>
        <form action="{{ route('reminders.store') }}" method="POST">
            @csrf
            @include('dashboard.features.reminders.reminders._form', ['selectedList' => null])
            <div style="display:flex; gap:10px; margin-top:24px; padding-top:20px; border-top:1px solid var(--border, #e5e5ea);">
                <button type="submit" class="btn-primary" style="flex:1; padding:12px; border-radius:10px; font-size:15px;">
                    Crear recordatorio
                </button>
                <button type="button" class="modal-cancel-btn"
                        onclick="document.getElementById('modalNewReminder').style.display='none'">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
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
                <button type="submit" class="btn-primary" style="flex:1; padding:12px; border-radius:10px; font-size:15px;">
                    Crear lista
                </button>
                <button type="button" class="modal-cancel-btn"
                        onclick="document.getElementById('modalNewList').style.display='none'">
                    Cancelar
                </button>
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
