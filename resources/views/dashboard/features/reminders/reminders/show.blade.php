@extends('dashboard.app.dashboard')

@section('content')
<div style="max-width:720px; margin:0 auto; padding:20px;">

    <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px; gap:12px;">
        <div style="display:flex; align-items:flex-start; gap:14px; flex:1;">
            <form action="{{ $reminder->is_completed ? route('reminders.uncomplete', $reminder) : route('reminders.complete', $reminder) }}" method="POST" style="padding-top:4px;">
                @csrf
                @method('PATCH')
                <button type="submit" style="width:28px; height:28px; border-radius:50%; border:2.5px solid {{ $reminder->is_completed ? '#34c759' : '#c7c7cc' }}; background:{{ $reminder->is_completed ? '#34c759' : 'transparent' }}; cursor:pointer; display:flex; align-items:center; justify-content:center; padding:0;">
                    @if($reminder->is_completed)
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 7l4 4 6-6" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    @endif
                </button>
            </form>

            <div>
                <h1 style="font-size:26px; font-weight:700; color:{{ $reminder->is_completed ? '#8e8e93' : '#1c1c1e' }}; margin:0; text-decoration:{{ $reminder->is_completed ? 'line-through' : 'none' }};">
                    {{ $reminder->title }}
                </h1>
                <div style="display:flex; align-items:center; gap:10px; margin-top:6px; flex-wrap:wrap;">
                    @if($reminder->list)
                        <span style="display:flex; align-items:center; gap:5px; font-size:13px; color:#8e8e93;">
                            <span style="width:8px; height:8px; border-radius:50%; background:{{ $reminder->list->color ?? '#007aff' }};"></span>
                            {{ $reminder->list->name }}
                        </span>
                    @endif

                    @if($reminder->status === 'archived')
                        <span style="background:#fff7ed; color:#c2410c; border:1px solid #fdba74; padding:2px 8px; border-radius:8px; font-size:12px; font-weight:500;">📦 Archivado</span>
                    @endif

                    @if($reminder->priority > 0)
                        @php
                            $pColors = [1 => '#007aff', 2 => '#ff9500', 3 => '#ff3b30'];
                            $pLabels = [1 => '🔵 Baja', 2 => '🟡 Media', 3 => '🔴 Alta'];
                        @endphp
                        <span style="font-size:13px; color:{{ $pColors[$reminder->priority] }}; font-weight:500;">{{ $pLabels[$reminder->priority] }}</span>
                    @endif

                    @if($reminder->due_at)
                        <span style="font-size:13px; color:{{ $reminder->is_overdue ? '#ff3b30' : '#8e8e93' }};">
                            📅 {{ $reminder->due_at->format('d/m/Y H:i') }}
                            @if($reminder->is_overdue) <strong>(Vencido)</strong> @endif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div style="display:flex; gap:8px; flex-shrink:0;">
            <a href="{{ route('reminders.edit', $reminder) }}"
               style="background:#007aff; color:#fff; padding:9px 16px; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600;">Editar</a>
        </div>
    </div>

    @if($reminder->notes)
        <div style="background:#fff; border:1px solid #e5e5ea; border-radius:14px; padding:18px; margin-bottom:18px;">
            <p style="font-size:12px; font-weight:600; color:#8e8e93; text-transform:uppercase; letter-spacing:.05em; margin-bottom:8px;">Notas</p>
            <p style="font-size:15px; color:#1c1c1e; margin:0; white-space:pre-wrap; line-height:1.6;">{{ $reminder->notes }}</p>
        </div>
    @endif

    @if($reminder->starts_at || $reminder->remind_at)
        <div style="background:#fff; border:1px solid #e5e5ea; border-radius:14px; padding:18px; margin-bottom:18px; display:flex; gap:24px; flex-wrap:wrap;">
            @if($reminder->starts_at)
                <div>
                    <p style="font-size:11px; font-weight:600; color:#8e8e93; text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px;">Inicio</p>
                    <p style="font-size:14px; color:#1c1c1e; margin:0;">{{ $reminder->starts_at->format('d/m/Y H:i') }}</p>
                </div>
            @endif
            @if($reminder->remind_at)
                <div>
                    <p style="font-size:11px; font-weight:600; color:#8e8e93; text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px;">Recordar a las</p>
                    <p style="font-size:14px; color:#1c1c1e; margin:0;">🔔 {{ $reminder->remind_at->format('d/m/Y H:i') }}</p>
                </div>
            @endif
        </div>
    @endif

    <div style="background:#fff; border:1px solid #e5e5ea; border-radius:14px; padding:18px; margin-bottom:18px;">
        <p style="font-size:12px; font-weight:600; color:#8e8e93; text-transform:uppercase; letter-spacing:.05em; margin-bottom:10px;">Mover a lista</p>
        <form action="{{ route('reminders.move', $reminder) }}" method="POST" style="display:flex; gap:10px;">
            @csrf
            @method('PUT')
            <select name="reminder_list_id"
                    style="flex:1; padding:9px 14px; border:1.5px solid #e5e5ea; border-radius:10px; font-size:14px; background:#fff; outline:none;">
                <option value="">Sin lista</option>
                @foreach($lists as $list)
                    <option value="{{ $list->id }}" {{ $reminder->reminder_list_id == $list->id ? 'selected' : '' }}>{{ $list->name }}</option>
                @endforeach
            </select>
            <button type="submit" style="background:#f2f2f7; color:#007aff; padding:9px 16px; border-radius:10px; border:none; cursor:pointer; font-size:14px; font-weight:600;">Mover</button>
        </form>
    </div>

    <div style="background:#fff; border:1px solid #e5e5ea; border-radius:14px; padding:20px;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <p style="font-size:15px; font-weight:700; color:#1c1c1e; margin:0;">
                Subtareas
                @if($reminder->subtasks->count() > 0)
                    <span style="font-size:13px; color:#8e8e93; font-weight:400; margin-left:6px;">
                        {{ $reminder->subtasks->where('is_completed', true)->count() }}/{{ $reminder->subtasks->count() }}
                    </span>
                @endif
            </p>
        </div>

        @forelse($reminder->subtasks as $subtask)
            {{-- data-subtask-id permite que subtask.js identifique cada fila para el edit inline --}}
            <div data-subtask-id="{{ $subtask->id }}"
                 style="display:flex; align-items:center; gap:10px; padding:8px 0; border-bottom:1px solid #f2f2f7;">

                <form action="{{ $subtask->is_completed ? route('reminders.subtasks.uncomplete', [$reminder, $subtask]) : route('reminders.subtasks.complete', [$reminder, $subtask]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" style="width:20px; height:20px; border-radius:50%; border:2px solid {{ $subtask->is_completed ? '#34c759' : '#c7c7cc' }}; background:{{ $subtask->is_completed ? '#34c759' : 'transparent' }}; cursor:pointer; display:flex; align-items:center; justify-content:center; padding:0; flex-shrink:0;">
                        @if($subtask->is_completed)
                            <svg width="10" height="10" viewBox="0 0 10 10" fill="none"><path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        @endif
                    </button>
                </form>

                <div style="flex:1;">
                    {{-- Texto visible: click abre el formulario de edición --}}
                    <span class="subtask-title"
                          style="font-size:14px; color:{{ $subtask->is_completed ? '#8e8e93' : '#1c1c1e' }}; text-decoration:{{ $subtask->is_completed ? 'line-through' : 'none' }}; cursor:pointer; display:block;">
                        {{ $subtask->title }}
                    </span>
                    {{-- Formulario de edición inline (oculto por defecto) --}}
                    <form class="subtask-edit-form"
                          action="{{ route('reminders.subtasks.update', [$reminder, $subtask]) }}"
                          method="POST"
                          style="display:none; gap:6px;">
                        @csrf
                        @method('PUT')
                        <input type="text" name="title" value="{{ $subtask->title }}"
                               style="flex:1; padding:4px 8px; border:1.5px solid #007aff; border-radius:7px; font-size:14px; outline:none;">
                        <button type="submit" style="background:#007aff; color:#fff; padding:4px 10px; border-radius:7px; border:none; cursor:pointer; font-size:13px;">✓</button>
                        <button type="button" class="subtask-cancel"
                                style="background:#f2f2f7; color:#8e8e93; padding:4px 8px; border-radius:7px; border:none; cursor:pointer; font-size:13px;">✕</button>
                    </form>
                </div>

                <form action="{{ route('reminders.subtasks.destroy', [$reminder, $subtask]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:transparent; border:none; cursor:pointer; color:#c7c7cc; font-size:16px; padding:4px;" onmouseover="this.style.color='#ff3b30'" onmouseout="this.style.color='#c7c7cc'">✕</button>
                </form>
            </div>
        @empty
            <p style="font-size:14px; color:#8e8e93; padding:12px 0; text-align:center;">Sin subtareas todavía.</p>
        @endforelse

        <form action="{{ route('reminders.subtasks.store', $reminder) }}" method="POST"
              style="display:flex; gap:10px; margin-top:14px;">
            @csrf
            <input type="text" name="title" placeholder="Añadir subtarea..."
                   style="flex:1; padding:10px 14px; border:1.5px solid #e5e5ea; border-radius:10px; font-size:14px; outline:none;">
            <button type="submit"
                    style="background:#007aff; color:#fff; padding:10px 18px; border-radius:10px; border:none; cursor:pointer; font-size:14px; font-weight:600;">
                Añadir
            </button>
        </form>
        @error('title')
            <p style="color:#ff3b30; font-size:12px; margin-top:4px;">{{ $message }}</p>
        @enderror
    </div>
</div>

@push('scripts')
    @vite('resources/js/dashboard/subtasks.js')
@endpush
@endsection
