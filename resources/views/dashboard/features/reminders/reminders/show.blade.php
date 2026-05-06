@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/reminders/reminders.css'])
@endpush

@section('content')
<div class="reminder-show-page">

    <a href="{{ route('reminders.index') }}" class="reminder-back-link">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M10 3L5 8l5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Recordatorios
    </a>

    <div class="reminder-show-header">
        <div class="reminder-show-header-left">
            <form action="{{ $reminder->is_completed ? route('reminders.uncomplete', $reminder) : route('reminders.complete', $reminder) }}" method="POST" class="reminder-show-complete-form">
                @csrf
                @method('PATCH')
                <button type="submit" class="reminder-show-complete-btn {{ $reminder->is_completed ? 'is-completed' : '' }}">
                    @if($reminder->is_completed)
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 7l4 4 6-6" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @endif
                </button>
            </form>

            <div class="reminder-show-title-wrap">
                <h1 class="{{ $reminder->is_completed ? 'show-title-completed' : '' }}">
                    {{ $reminder->title }}
                </h1>
                <div class="reminder-show-meta">
                    @if($reminder->list)
                        <span class="reminder-show-meta-item">
                            <span class="reminder-show-list-dot" style="background:{{ $reminder->list->color ?? '#007aff' }};"></span>
                            {{ $reminder->list->name }}
                        </span>
                    @endif

                    @if($reminder->status === 'archived')
                        <span class="badge-archived">Archivado</span>
                    @endif

                    @if($reminder->priority > 0)
                        @php
                            $pClasses = [1 => 'priority-badge priority-badge-low', 2 => 'priority-badge priority-badge-medium', 3 => 'priority-badge priority-badge-high'];
                            $pLabels  = [1 => 'Baja', 2 => 'Media', 3 => 'Alta'];
                        @endphp
                        <span class="{{ $pClasses[$reminder->priority] }}">{{ $pLabels[$reminder->priority] }}</span>
                    @endif

                    @if($reminder->due_at)
                        <span class="reminder-show-due {{ $reminder->is_overdue ? 'overdue' : '' }}">
                            <iconify-icon icon="lucide:calendar" width="13"></iconify-icon>
                            {{ $reminder->due_at->format('d/m/Y H:i') }}
                            @if($reminder->is_overdue) <strong>· Vencido</strong> @endif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="reminder-show-actions">
            <a href="{{ route('reminders.edit', $reminder) }}" class="reminder-show-edit-btn">Editar</a>
        </div>
    </div>

    @if($reminder->notes)
        <div class="reminder-detail-card">
            <p class="reminder-detail-section-label">Notas</p>
            <p class="reminder-detail-text">{{ $reminder->notes }}</p>
        </div>
    @endif

    @if($reminder->starts_at || $reminder->remind_at)
        <div class="reminder-dates-card">
            @if($reminder->starts_at)
                <div>
                    <p class="reminder-date-field-label">Inicio</p>
                    <p class="reminder-date-field-value">{{ $reminder->starts_at->format('d/m/Y H:i') }}</p>
                </div>
            @endif
            @if($reminder->remind_at)
                <div>
                    <p class="reminder-date-field-label">Recordar a las</p>
                    <p class="reminder-date-field-value">
                        <iconify-icon icon="lucide:bell" width="13"></iconify-icon>
                        {{ $reminder->remind_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            @endif
        </div>
    @endif

    <div class="reminder-subtasks-card">
        <div class="reminder-subtasks-header">
            <p class="reminder-subtasks-title">
                Subtareas
                @if($reminder->subtasks->count() > 0)
                    <span class="reminder-subtasks-count">
                        {{ $reminder->subtasks->where('is_completed', true)->count() }}/{{ $reminder->subtasks->count() }}
                    </span>
                @endif
            </p>
        </div>

        @forelse($reminder->subtasks as $subtask)
            <div data-subtask-id="{{ $subtask->id }}" class="subtask-row">

                <form action="{{ $subtask->is_completed ? route('reminders.subtasks.uncomplete', [$reminder, $subtask]) : route('reminders.subtasks.complete', [$reminder, $subtask]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="subtask-toggle-btn {{ $subtask->is_completed ? 'is-completed' : '' }}">
                        @if($subtask->is_completed)
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @endif
                    </button>
                </form>

                <div class="subtask-body">
                    <span class="subtask-title-span {{ $subtask->is_completed ? 'subtask-completed' : '' }}">
                        {{ $subtask->title }}
                    </span>
                    <form class="subtask-edit-form"
                          action="{{ route('reminders.subtasks.update', [$reminder, $subtask]) }}"
                          method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" name="title" value="{{ $subtask->title }}" class="subtask-edit-input">
                        <button type="submit" class="subtask-edit-save">✓</button>
                        <button type="button" class="subtask-cancel subtask-edit-cancel">✕</button>
                    </form>
                </div>

                <form action="{{ route('reminders.subtasks.destroy', [$reminder, $subtask]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="subtask-delete-btn">✕</button>
                </form>
            </div>
        @empty
            <p class="subtask-empty-msg">Sin subtareas todavía.</p>
        @endforelse

        <form action="{{ route('reminders.subtasks.store', $reminder) }}" method="POST" class="subtask-add-form">
            @csrf
            <input type="text" name="title" placeholder="Añadir subtarea..." class="subtask-add-input">
            <button type="submit" class="subtask-add-btn">
                Añadir
            </button>
        </form>
        @error('title')
            <p class="reminder-error-msg">{{ $message }}</p>
        @enderror
    </div>
</div>

@push('scripts')
    @vite('resources/js/dashboard/subtasks.js')
@endpush
@endsection

