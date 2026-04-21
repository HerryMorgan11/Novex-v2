{{-- Formulario reutilizable para crear/editar recordatorios --}}
<div class="reminder-fields">

    <div>
        <label class="reminder-field-label">
            Título <span class="reminder-field-required">*</span>
        </label>
        <input type="text" name="title" value="{{ old('title', $reminder->title ?? '') }}"
               placeholder="¿Qué necesitas recordar?"
               autofocus
               style="width:100%; padding:11px 14px; border:1.5px solid {{ $errors->has('title') ? '#ff3b30' : '#e5e5ea' }}; border-radius:10px; font-size:15px; outline:none; box-sizing:border-box;">
        @error('title')
            <p class="reminder-error-msg">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="reminder-field-label">Notas</label>
        <textarea name="notes" rows="3"
                  placeholder="Notas adicionales..."
                  class="reminder-textarea">{{ old('notes', $reminder->notes ?? '') }}</textarea>
    </div>

    <div class="reminder-fields-row">
        <div class="reminder-field-flex-2">
            <label class="reminder-field-label">Lista</label>
            <select name="reminder_list_id" class="reminder-select">
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

        <div class="reminder-field-flex-1">
            <label class="reminder-field-label">Prioridad</label>
            <select name="priority" class="reminder-select">
                <option value="0" {{ old('priority', $reminder->priority ?? 0) == 0 ? 'selected' : '' }}>⚪ Ninguna</option>
                <option value="1" {{ old('priority', $reminder->priority ?? 0) == 1 ? 'selected' : '' }}>🔵 Baja</option>
                <option value="2" {{ old('priority', $reminder->priority ?? 0) == 2 ? 'selected' : '' }}>🟡 Media</option>
                <option value="3" {{ old('priority', $reminder->priority ?? 0) == 3 ? 'selected' : '' }}>🔴 Alta</option>
            </select>
        </div>
    </div>

    <div class="reminder-fields-row rem-fields-row-wrap">
        <div class="reminder-field-flex-min">
            <label class="reminder-field-label">Fecha de inicio</label>
            <input type="datetime-local" name="starts_at"
                   value="{{ old('starts_at', isset($reminder) && $reminder->starts_at ? $reminder->starts_at->format('Y-m-d\TH:i') : '') }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid {{ $errors->has('starts_at') ? '#ff3b30' : '#e5e5ea' }}; border-radius:10px; font-size:14px; outline:none; box-sizing:border-box;">
            @error('starts_at')
                <p class="reminder-error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="reminder-field-flex-min">
            <label class="reminder-field-label">Fecha de vencimiento</label>
            <input type="datetime-local" name="due_at"
                   value="{{ old('due_at', isset($reminder) && $reminder->due_at ? $reminder->due_at->format('Y-m-d\TH:i') : '') }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid {{ $errors->has('due_at') ? '#ff3b30' : '#e5e5ea' }}; border-radius:10px; font-size:14px; outline:none; box-sizing:border-box;">
            @error('due_at')
                <p class="reminder-error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="reminder-field-flex-min">
            <label class="reminder-field-label">Recordar a las</label>
            <input type="datetime-local" name="remind_at"
                   value="{{ old('remind_at', isset($reminder) && $reminder->remind_at ? $reminder->remind_at->format('Y-m-d\TH:i') : '') }}"
                   class="reminder-input-sm">
        </div>
    </div>

    <div class="reminder-checkbox-row">
        <input type="hidden" name="all_day" value="0">
        <input type="checkbox" name="all_day" value="1" id="all_day"
               {{ old('all_day', $reminder->all_day ?? false) ? 'checked' : '' }}
               class="reminder-checkbox-input">
        <label for="all_day" class="reminder-checkbox-label">Todo el día (sin hora específica)</label>
    </div>

</div>
