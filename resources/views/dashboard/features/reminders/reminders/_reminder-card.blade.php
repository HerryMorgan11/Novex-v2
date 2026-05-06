{{-- Tarjeta de recordatorio para el listado --}}
<div class="reminder-card" style="border:1px solid {{ $reminder->is_overdue ? '#fca5a5' : '#e5e5ea' }};"
     onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow='none'">

    <div class="reminder-card-toggle-wrap">
        <form action="{{ $reminder->is_completed ? route('reminders.uncomplete', $reminder) : route('reminders.complete', $reminder) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="reminder-card-toggle-btn" style="border:2px solid {{ $reminder->is_completed ? '#34c759' : '#c7c7cc' }}; background:{{ $reminder->is_completed ? '#34c759' : 'transparent' }};">
                @if($reminder->is_completed)
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 6l3 3 5-5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                @endif
            </button>
        </form>
    </div>

    <div class="reminder-card-body">
        <div class="reminder-card-title-row">
            <a href="{{ route('reminders.show', $reminder) }}"
               class="reminder-card-title"
               style="color:{{ $reminder->is_completed ? '#8e8e93' : '#1c1c1e' }}; text-decoration:{{ $reminder->is_completed ? 'line-through' : 'none' }};">
                {{ $reminder->title }}
            </a>

            @if($reminder->priority > 0)
                @php
                    $priorityColors = [1 => '#007aff', 2 => '#ff9500', 3 => '#ff3b30'];
                    $priorityLabels = [1 => '!', 2 => '!!', 3 => '!!!'];
                @endphp
                <span style="color:{{ $priorityColors[$reminder->priority] }}; font-size:13px; font-weight:700;">{{ $priorityLabels[$reminder->priority] }}</span>
            @endif
        </div>

        <div class="reminder-card-meta-row">
            @if($reminder->list)
                <span class="reminder-card-meta-item">
                    <span class="reminder-card-list-dot" style="background:{{ $reminder->list->color ?? '#007aff' }};"></span>
                    {{ $reminder->list->name }}
                </span>
            @endif

            @if($reminder->due_at)
                <span style="font-size:12px; color:{{ $reminder->is_overdue ? '#ff3b30' : '#8e8e93' }};">
                    📅 {{ $reminder->due_at->format('d/m/y') }}
                    @if($reminder->is_overdue) (vencido) @endif
                </span>
            @endif

            @if($reminder->subtasks->count() > 0)
                <span class="reminder-card-subtask-count">
                    ◻ {{ $reminder->subtasks->where('is_completed', true)->count() }}/{{ $reminder->subtasks->count() }}
                </span>
            @endif
        </div>
    </div>

    <div class="reminder-card-actions">
        <a href="{{ route('reminders.edit', $reminder) }}" class="reminder-card-edit-btn">Editar</a>
        <form action="{{ route('reminders.destroy', $reminder) }}" method="POST"
              onsubmit="return confirm('¿Eliminar este recordatorio?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="reminder-card-delete-btn">✕</button>
        </form>
    </div>
</div>
