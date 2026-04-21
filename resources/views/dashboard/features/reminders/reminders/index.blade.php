@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/reminders/reminders.css'])
@endpush

@section('content')
<div class="reminders-container">
    <!-- Sidebar Reminders -->
    <aside class="reminders-sidebar-inner">

        <!-- Smart Lists -->
        <div class="reminder-filters">
            <a href="{{ route('reminders.index', ['filter' => 'all']) }}" class="filter-card" title="Todos">
                <div class="filter-card-header">
                    <iconify-icon icon="lucide:layout-list" width="20"></iconify-icon>
                    <div class="filter-card-count">{{ $allCount }}</div>
                </div>
                <div class="filter-card-label">Todos</div>
            </a>
            <a href="{{ route('reminders.index', ['filter' => 'pending']) }}" class="filter-card" title="Pendientes">
                <div class="filter-card-header">
                    <iconify-icon icon="lucide:clock" width="20"></iconify-icon>
                    <div class="filter-card-count">{{ $pendingCount }}</div>
                </div>
                <div class="filter-card-label">Pendientes</div>
            </a>
            <a href="{{ route('reminders.index', ['filter' => 'completed']) }}" class="filter-card" title="Completados">
                <div class="filter-card-header">
                    <iconify-icon icon="lucide:check-circle-2" width="20"></iconify-icon>
                    <div class="filter-card-count">{{ $completedCount }}</div>
                </div>
                <div class="filter-card-label">Completados</div>
            </a>
            <a href="{{ route('reminders.index', ['filter' => 'overdue']) }}" class="filter-card" title="Vencidos">
                <div class="filter-card-header">
                    <iconify-icon icon="lucide:alert-circle" width="20"></iconify-icon>
                    <div class="filter-card-count">{{ $overdueCount }}</div>
                </div>
                <div class="filter-card-label">Vencidos</div>
            </a>
        </div>

        <!-- Custom Lists -->
        <div class="reminder-sidebar-header">
            <h3>Mis listas</h3>
            <button onclick="openNewListModal()"
                    class="reminder-sidebar-add-btn"
                    title="Nueva lista">+</button>
        </div>
        <div>
            @forelse($lists as $list)
                <a href="{{ route('reminders.index', ['list' => $list->id, 'filter' => 'all']) }}" class="list-item">
                    <div class="rem-list-item-inner">
                        <div class="list-color" style="background-color: {{ $list->color ?? '#007AFF' }}"></div>
                        <span>{{ $list->name }}</span>
                    </div>
                    <span class="list-count">{{ $list->reminders_count }}</span>
                </a>
            @empty
                <p class="reminder-no-lists">Sin listas</p>
            @endforelse
        </div>

        <div class="reminders-manage-wrap">
            <a href="{{ route('reminders.lists.index') }}" class="reminders-manage-link"
               onmouseover="this.style.background='var(--surface-2)'" onmouseout="this.style.background='transparent'">
                <iconify-icon icon="lucide:settings-2" width="14"></iconify-icon>
                Gestionar listas
            </a>
        </div>

    </aside>

    <!-- Main Content -->
    <main class="reminders-main">
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
            <div class="reminders-current-list-meta">
                <span class="reminders-current-list-dot" style="background:{{ $currentList->color ?? '#007aff' }};"></span>
                <iconify-icon icon="lucide:list" width="13"></iconify-icon>
                <span>{{ $currentList->name }}</span>
                <span class="reminders-current-list-separator">·</span>
                <a href="{{ route('reminders.index') }}" class="reminders-current-list-all-link">Ver todas</a>
            </div>
            @endif
            @if($currentList)
            @php $listId = $currentList->id; $lf = request('filter', 'all'); @endphp
            <div class="reminders-list-filter-tabs">
                @foreach([
                    ['all',       'Todas',       'lucide:layout-list'],
                    ['pending',   'Pendientes',  'lucide:clock'],
                    ['overdue',   'Vencidas',    'lucide:alert-circle'],
                    ['completed', 'Completadas', 'lucide:check-circle-2'],
                ] as [$val, $label, $icon])
                <a href="{{ route('reminders.index', ['list' => $listId, 'filter' => $val]) }}"
                   class="reminders-list-filter-tab"
                   style="background:{{ $lf === $val ? 'var(--accent,#007aff)' : 'var(--surface-2,#f2f2f7)' }}; color:{{ $lf === $val ? 'var(--accent-fg,#fff)' : 'var(--fg,#555)' }};">
                    <iconify-icon icon="{{ $icon }}" width="13"></iconify-icon>
                    {{ $label }}
                    @if($val === 'overdue' && $currentList->overdueRemindersCount() > 0)
                        <span class="reminders-list-filter-tab-badge">{{ $currentList->overdueRemindersCount() }}</span>
                    @endif
                </a>
                @endforeach
            </div>
            @endif
            <span class="reminders-subtitle">{{ now()->format('l, M d') }}</span>
            <button onclick="document.getElementById('modalNewReminder').style.display='flex'"
                    class="btn-primary reminders-add-btn">
                + Agregar recordatorio
            </button>
        </div>

        <!-- Reminders List -->
        @forelse($reminders as $reminder)
            <form action="{{ route('reminders.complete', $reminder) }}" method="POST" class="reminder-form-inline">
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
                                            <iconify-icon icon="lucide:check" width="14" class="rem-subtask-icon-primary"></iconify-icon>
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

                    <a href="{{ route('reminders.show', $reminder) }}" class="btn-primary btn-primary-sm">
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
                <p class="reminder-pagination">No hay recordatorios para esta vista</p>
                <a href="{{ route('reminders.create') }}" class="btn-primary">Crear Recordatorio</a>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($reminders->hasPages())
            <div class="reminder-pagination">
                {{ $reminders->links() }}
            </div>
        @endif
    </main>
</div>

{{-- Modal: Nuevo Recordatorio --}}
<div id="modalNewReminder"
     class="reminder-modal-overlay"
     onclick="if(event.target===this)this.style.display='none'">
    <div class="modal-panel rem-modal-panel-md">
        <div class="modal-header">
            <h2 class="modal-title">Nuevo recordatorio</h2>
            <button onclick="document.getElementById('modalNewReminder').style.display='none'" class="modal-close-btn">&times;</button>
        </div>
        <form action="{{ route('reminders.store') }}" method="POST">
            @csrf
            @include('dashboard.features.reminders.reminders._form', ['selectedList' => null])
            <div class="modal-footer">
                <button type="submit" class="btn-primary rem-modal-submit-btn">
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
                <button type="submit" class="btn-primary-lists">
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
