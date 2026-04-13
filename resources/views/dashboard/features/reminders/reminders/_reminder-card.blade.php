{{-- Tarjeta de recordatorio para el listado --}}
<div style="background:#fff; border:1px solid {{ $reminder->is_overdue ? '#fca5a5' : '#e5e5ea' }}; border-radius:12px; padding:14px 16px; display:flex; align-items:flex-start; gap:12px; transition:box-shadow .2s;"
     onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow='none'">

    <div style="padding-top:2px;">
        <form action="{{ $reminder->is_completed ? route('reminders.uncomplete', $reminder) : route('reminders.complete', $reminder) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" style="width:22px; height:22px; border-radius:50%; border:2px solid {{ $reminder->is_completed ? '#34c759' : '#c7c7cc' }}; background:{{ $reminder->is_completed ? '#34c759' : 'transparent' }}; cursor:pointer; display:flex; align-items:center; justify-content:center; padding:0; flex-shrink:0;">
                @if($reminder->is_completed)
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 6l3 3 5-5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                @endif
            </button>
        </form>
    </div>

    <div style="flex:1; min-width:0;">
        <div style="display:flex; align-items:baseline; gap:8px; flex-wrap:wrap;">
            <a href="{{ route('reminders.show', $reminder) }}"
               style="font-size:15px; font-weight:500; color:{{ $reminder->is_completed ? '#8e8e93' : '#1c1c1e' }}; text-decoration:{{ $reminder->is_completed ? 'line-through' : 'none' }}; overflow:hidden; text-overflow:ellipsis;">
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

        <div style="display:flex; align-items:center; gap:10px; margin-top:4px; flex-wrap:wrap;">
            @if($reminder->list)
                <span style="font-size:12px; color:#8e8e93; display:flex; align-items:center; gap:4px;">
                    <span style="width:6px; height:6px; border-radius:50%; background:{{ $reminder->list->color ?? '#007aff' }};"></span>
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
                <span style="font-size:12px; color:#8e8e93;">
                    ◻ {{ $reminder->subtasks->where('is_completed', true)->count() }}/{{ $reminder->subtasks->count() }}
                </span>
            @endif
        </div>
    </div>

    <div style="display:flex; gap:6px; flex-shrink:0;">
        <a href="{{ route('reminders.edit', $reminder) }}"
           style="background:#f2f2f7; color:#8e8e93; padding:6px 10px; border-radius:7px; text-decoration:none; font-size:12px;">Editar</a>
        <form action="{{ route('reminders.destroy', $reminder) }}" method="POST"
              onsubmit="return confirm('¿Eliminar este recordatorio?')">
            @csrf
            @method('DELETE')
            <button type="submit" style="background:#fee2e2; color:#dc2626; padding:6px 10px; border-radius:7px; border:none; cursor:pointer; font-size:12px;">✕</button>
        </form>
    </div>
</div>
