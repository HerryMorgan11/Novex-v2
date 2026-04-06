{{-- Formulario reutilizable para crear/editar recordatorios --}}
<div style="display:flex; flex-direction:column; gap:18px;">

    <div>
        <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">
            Título <span style="color:#ff3b30;">*</span>
        </label>
        <input type="text" name="title" value="{{ old('title', $reminder->title ?? '') }}"
               placeholder="¿Qué necesitas recordar?"
               autofocus
               style="width:100%; padding:11px 14px; border:1.5px solid {{ $errors->has('title') ? '#ff3b30' : '#e5e5ea' }}; border-radius:10px; font-size:15px; outline:none; box-sizing:border-box;">
        @error('title')
            <p style="color:#ff3b30; font-size:12px; margin-top:4px;">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">Notas</label>
        <textarea name="notes" rows="3"
                  placeholder="Notas adicionales..."
                  style="width:100%; padding:11px 14px; border:1.5px solid #e5e5ea; border-radius:10px; font-size:14px; outline:none; resize:vertical; box-sizing:border-box;">{{ old('notes', $reminder->notes ?? '') }}</textarea>
    </div>

    <div style="display:flex; gap:14px;">
        <div style="flex:2;">
            <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">Lista</label>
            <select name="reminder_list_id"
                    style="width:100%; padding:10px 14px; border:1.5px solid #e5e5ea; border-radius:10px; font-size:14px; background:#fff; outline:none;">
                <option value="">Sin lista</option>
                @foreach($lists as $list)
                    <option value="{{ $list->id }}"
                        style="color:{{ $list->color ?? 'inherit' }}"
                        {{ old('reminder_list_id', $reminder->reminder_list_id ?? $selectedList?->id) == $list->id ? 'selected' : '' }}>
                        {{ $list->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="flex:1;">
            <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">Prioridad</label>
            <select name="priority"
                    style="width:100%; padding:10px 14px; border:1.5px solid #e5e5ea; border-radius:10px; font-size:14px; background:#fff; outline:none;">
                <option value="0" {{ old('priority', $reminder->priority ?? 0) == 0 ? 'selected' : '' }}>⚪ Ninguna</option>
                <option value="1" {{ old('priority', $reminder->priority ?? 0) == 1 ? 'selected' : '' }}>🔵 Baja</option>
                <option value="2" {{ old('priority', $reminder->priority ?? 0) == 2 ? 'selected' : '' }}>🟡 Media</option>
                <option value="3" {{ old('priority', $reminder->priority ?? 0) == 3 ? 'selected' : '' }}>🔴 Alta</option>
            </select>
        </div>
    </div>

    <div style="display:flex; gap:14px; flex-wrap:wrap;">
        <div style="flex:1; min-width:160px;">
            <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">Fecha de inicio</label>
            <input type="datetime-local" name="starts_at"
                   value="{{ old('starts_at', isset($reminder) && $reminder->starts_at ? $reminder->starts_at->format('Y-m-d\TH:i') : '') }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid {{ $errors->has('starts_at') ? '#ff3b30' : '#e5e5ea' }}; border-radius:10px; font-size:14px; outline:none; box-sizing:border-box;">
            @error('starts_at')
                <p style="color:#ff3b30; font-size:12px; margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="flex:1; min-width:160px;">
            <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">Fecha de vencimiento</label>
            <input type="datetime-local" name="due_at"
                   value="{{ old('due_at', isset($reminder) && $reminder->due_at ? $reminder->due_at->format('Y-m-d\TH:i') : '') }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid {{ $errors->has('due_at') ? '#ff3b30' : '#e5e5ea' }}; border-radius:10px; font-size:14px; outline:none; box-sizing:border-box;">
            @error('due_at')
                <p style="color:#ff3b30; font-size:12px; margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="flex:1; min-width:160px;">
            <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">Recordar a las</label>
            <input type="datetime-local" name="remind_at"
                   value="{{ old('remind_at', isset($reminder) && $reminder->remind_at ? $reminder->remind_at->format('Y-m-d\TH:i') : '') }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid #e5e5ea; border-radius:10px; font-size:14px; outline:none; box-sizing:border-box;">
        </div>
    </div>

    <div style="display:flex; align-items:center; gap:10px;">
        <input type="hidden" name="all_day" value="0">
        <input type="checkbox" name="all_day" value="1" id="all_day"
               {{ old('all_day', $reminder->all_day ?? false) ? 'checked' : '' }}
               style="width:18px; height:18px; accent-color:#007aff; cursor:pointer;">
        <label for="all_day" style="font-size:14px; color:#1c1c1e; cursor:pointer;">Todo el día (sin hora específica)</label>
    </div>

    @if($tags->isNotEmpty())
        <div>
            <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:8px;">Etiquetas</label>
            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                @php
                    $selectedTagIds = old('tag_ids', isset($reminder) ? $reminder->tags->pluck('id')->toArray() : []);
                @endphp
                @foreach($tags as $tag)
                    <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
                        <input type="checkbox" name="tag_ids[]" value="{{ $tag->id }}"
                               {{ in_array($tag->id, $selectedTagIds) ? 'checked' : '' }}
                               style="accent-color:{{ $tag->color ?? '#007aff' }}; width:15px; height:15px;">
                        <span style="background:{{ $tag->color ?? '#e5e5ea' }}20; border:1px solid {{ $tag->color ?? '#e5e5ea' }}; color:{{ $tag->color ?? '#8e8e93' }}; padding:3px 10px; border-radius:10px; font-size:13px; font-weight:500;">{{ $tag->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif

</div>
