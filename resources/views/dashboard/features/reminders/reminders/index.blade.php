@extends('dashboard.app.dashboard')

@section('content')
<div class="reminders-container" style="display: flex; gap: 20px; padding: 20px;">
    <!-- Sidebar Reminders -->
    <aside class="reminders-sidebar" style="width: 280px; flex-shrink: 0;">
        <style>
            .reminders-sidebar {
                background: var(--sidebar-bg, #f5f5f5);
                border-radius: 8px;
                padding: 16px;
                border: 1px solid var(--border-color, #e0e0e0);
                max-height: calc(100vh - 120px);
                overflow-y: auto;
            }
            .dark .reminders-sidebar {
                background: var(--sidebar-bg, #1a1a1a);
                border-color: var(--border-color, #333);
            }
            .reminders-sidebar h3 {
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: var(--text-muted, #999);
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
                background: var(--card-bg, #ffffff);
                border: 1px solid var(--border-color, #e0e0e0);
                text-decoration: none;
                cursor: pointer;
                transition: all 0.2s;
                text-align: center;
                color: var(--text-primary, #333);
            }
            .dark .filter-card {
                background: var(--card-bg, #2a2a2a);
                border-color: var(--border-color, #444);
                color: var(--text-primary, #f0f0f0);
            }
            .filter-card:hover {
                background: var(--card-hover-bg, #f0f0f0);
                transform: translateY(-2px);
            }
            .dark .filter-card:hover {
                background: var(--card-hover-bg, #333);
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
                color: var(--text-primary, #333);
            }
            .dark .filter-card-label {
                color: var(--text-primary, #f0f0f0);
            }
            .filter-card-count {
                font-size: 16px;
                font-weight: 700;
                color: var(--primary-color, #007AFF);
                margin-top: 4px;
            }
            .list-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 10px 8px;
                border-radius: 6px;
                color: var(--text-primary, #333);
                text-decoration: none;
                transition: all 0.2s;
                font-size: 14px;
                margin-bottom: 4px;
            }
            .dark .list-item {
                color: var(--text-primary, #f0f0f0);
            }
            .list-item:hover {
                background: var(--hover-bg, #f0f0f0);
            }
            .dark .list-item:hover {
                background: var(--hover-bg, #333);
            }
            .list-color {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                flex-shrink: 0;
            }
            .list-count {
                font-size: 12px;
                color: var(--text-muted, #999);
                font-weight: 600;
            }
        </style>

        <!-- Smart Lists -->
        <div class="reminder-filters">
            <a href="{{ route('reminders.index', ['filter' => 'today']) }}" class="filter-card" title="Today">
                <div class="filter-card-icon">
                    <iconify-icon icon="lucide:calendar-today" width="20"></iconify-icon>
                </div>
                <div class="filter-card-count">{{ $todayCount }}</div>
                <div class="filter-card-label">Today</div>
            </a>
            <a href="{{ route('reminders.index', ['filter' => 'pending']) }}" class="filter-card" title="Pending">
                <div class="filter-card-icon">
                    <iconify-icon icon="lucide:clock" width="20"></iconify-icon>
                </div>
                <div class="filter-card-count">{{ $pendingCount }}</div>
                <div class="filter-card-label">Pending</div>
            </a>
            <a href="{{ route('reminders.index', ['filter' => 'completed']) }}" class="filter-card" title="Completed">
                <div class="filter-card-icon">
                    <iconify-icon icon="lucide:check-circle-2" width="20"></iconify-icon>
                </div>
                <div class="filter-card-count">{{ $completedCount }}</div>
                <div class="filter-card-label">Completed</div>
            </a>
            <a href="{{ route('reminders.index', ['filter' => 'overdue']) }}" class="filter-card" title="Overdue">
                <div class="filter-card-icon">
                    <iconify-icon icon="lucide:alert-circle" width="20"></iconify-icon>
                </div>
                <div class="filter-card-count">{{ $overdueCount }}</div>
                <div class="filter-card-label">Overdue</div>
            </a>
        </div>

        <!-- Custom Lists -->
        <h3>My Lists</h3>
        <div style="space-y: 4px;">
            @forelse($lists as $list)
                <a href="{{ route('reminders.index', ['list' => $list->id]) }}" class="list-item">
                    <div style="display: flex; align-items: center; gap: 10px; flex: 1;">
                        <div class="list-color" style="background-color: {{ $list->color ?? '#007AFF' }}"></div>
                        <span>{{ $list->name }}</span>
                    </div>
                    <span class="list-count">{{ $list->reminders_count }}</span>
                </a>
            @empty
                <p style="font-size: 12px; color: var(--text-muted, #999); padding: 8px;">No lists yet</p>
            @endforelse
        </div>

        <h3 style="margin-top: 24px;">Tags</h3>
        <div style="space-y: 4px;">
            @forelse($tags as $tag)
                <a href="{{ route('reminders.index', ['tag' => $tag->id]) }}" class="list-item">
                    <span>#{{ $tag->name }}</span>
                    <span class="list-count">{{ $tag->reminders_count }}</span>
                </a>
            @empty
                <p style="font-size: 12px; color: var(--text-muted, #999); padding: 8px;">No tags yet</p>
            @endforelse
        </div>
    </aside>

    <!-- Main Content -->
    <main class="reminders-main" style="flex: 1; min-width: 0;">
        <style>
            .reminders-main {
                background: var(--content-bg, #ffffff);
                border-radius: 8px;
                padding: 24px;
                border: 1px solid var(--border-color, #e0e0e0);
            }
            .dark .reminders-main {
                background: var(--content-bg, #1a1a1a);
                border-color: var(--border-color, #333);
            }
            .reminders-header {
                margin-bottom: 24px;
                display: flex;
                align-items: baseline;
                gap: 16px;
            }
            .reminders-title {
                font-size: 28px;
                font-weight: 700;
                color: var(--text-primary, #333);
                margin: 0;
            }
            .dark .reminders-title {
                color: var(--text-primary, #f0f0f0);
            }
            .reminders-subtitle {
                font-size: 14px;
                color: var(--text-muted, #999);
            }
            .reminder-item {
                display: flex;
                align-items: flex-start;
                gap: 12px;
                padding: 16px;
                margin-bottom: 12px;
                background: var(--item-bg, #fafafa);
                border: 1px solid var(--border-color, #e0e0e0);
                border-radius: 8px;
                transition: all 0.2s;
            }
            .dark .reminder-item {
                background: var(--item-bg, #2a2a2a);
                border-color: var(--border-color, #444);
            }
            .reminder-item:hover {
                background: var(--item-hover-bg, #f5f5f5);
                border-color: var(--primary-color, #007AFF);
            }
            .dark .reminder-item:hover {
                background: var(--item-hover-bg, #333);
                border-color: var(--primary-color, #007AFF);
            }
            .reminder-item.completed {
                opacity: 0.6;
                background: var(--completed-bg, #f0f0f0);
            }
            .dark .reminder-item.completed {
                background: var(--completed-bg, #262626);
            }
            .reminder-checkbox {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                border: 2px solid var(--border-color, #e0e0e0);
                cursor: pointer;
                flex-shrink: 0;
                margin-top: 2px;
            }
            .dark .reminder-checkbox {
                border-color: var(--border-color, #444);
            }
            .reminder-content {
                flex: 1;
            }
            .reminder-title {
                font-size: 16px;
                font-weight: 600;
                color: var(--text-primary, #333);
                margin: 0;
                text-decoration: none;
                cursor: pointer;
                transition: color 0.2s;
            }
            .dark .reminder-title {
                color: var(--text-primary, #f0f0f0);
            }
            .reminder-title:hover {
                color: var(--primary-color, #007AFF);
            }
            .reminder-item.completed .reminder-title {
                text-decoration: line-through;
                color: var(--text-muted, #999);
            }
            .reminder-meta {
                display: flex;
                align-items: center;
                gap: 12px;
                margin-top: 8px;
                font-size: 12px;
                color: var(--text-muted, #999);
                flex-wrap: wrap;
            }
            .reminder-meta-item {
                display: flex;
                align-items: center;
                gap: 4px;
            }
            .reminder-notes {
                font-size: 13px;
                color: var(--text-secondary, #666);
                margin-top: 8px;
                line-height: 1.4;
            }
            .dark .reminder-notes {
                color: var(--text-secondary, #ccc);
            }
            .reminder-subtasks {
                margin-top: 12px;
                padding-top: 12px;
                border-top: 1px solid var(--border-color, #e0e0e0);
                space-y: 4px;
            }
            .dark .reminder-subtasks {
                border-color: var(--border-color, #444);
            }
            .subtask-item {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 13px;
                color: var(--text-secondary, #666);
                margin-bottom: 4px;
            }
            .dark .subtask-item {
                color: var(--text-secondary, #ccc);
            }
            .empty-state {
                text-align: center;
                padding: 40px 20px;
                color: var(--text-muted, #999);
            }
            .empty-state-icon {
                font-size: 48px;
                margin-bottom: 12px;
            }
            .empty-state-title {
                font-size: 16px;
                font-weight: 600;
                margin-bottom: 8px;
                color: var(--text-primary, #333);
            }
            .dark .empty-state-title {
                color: var(--text-primary, #f0f0f0);
            }
            .btn-primary {
                display: inline-block;
                background: var(--primary-color, #007AFF);
                color: white;
                padding: 10px 20px;
                border-radius: 6px;
                text-decoration: none;
                font-weight: 600;
                font-size: 14px;
                transition: all 0.2s;
                border: none;
                cursor: pointer;
            }
            .btn-primary:hover {
                opacity: 0.9;
                transform: translateY(-2px);
            }
            .priority-high { color: #FF3B30; font-weight: 700; }
            .priority-medium { color: #FF9500; font-weight: 700; }
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
            <span class="reminders-subtitle">{{ now()->format('l, M d') }}</span>
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
                            @if($reminder->tags->count() > 0)
                                <div class="reminder-meta-item">
                                    <iconify-icon icon="lucide:tag" width="14"></iconify-icon>
                                    @foreach($reminder->tags->take(2) as $tag)
                                        <span>#{{ $tag->name }}</span>
                                    @endforeach
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
                                        <span>+{{ $reminder->subtasks->count() - 3 }} more</span>
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
@endsection
