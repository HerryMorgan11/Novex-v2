{{-- Formulario reutilizable para crear/editar listas --}}
@php
    $presetColors = ['#007aff','#34c759','#ff3b30','#ff9500','#ffcc00','#af52de','#ff2d55','#5ac8fa','#30b0c7','#a2845e','#8e8e93','#1c1c1e'];
    $currentColor = old('color', $list->color ?? '#007aff');
@endphp
<div class="reminder-fields">

    {{-- Nombre --}}
    <div>
        <label class="reminder-field-label">
            Nombre <span class="reminder-field-required">*</span>
        </label>
        <input type="text" name="name" id="list-name-input"
               value="{{ old('name', $list->name ?? '') }}"
               placeholder="Nombre de la lista"
               autocomplete="off"
               class="reminder-input {{ $errors->has('name') ? 'reminder-input-error' : '' }}">
        @error('name')
            <p class="reminder-error-msg">{{ $message }}</p>
        @enderror
    </div>

    {{-- Color --}}
    <div>
        <label class="reminder-field-label">Color</label>
        <input type="hidden" name="color" id="list-color-input" value="{{ $currentColor }}">
        <div class="rem-color-swatches">
            @foreach($presetColors as $presetColor)
                <button type="button"
                        onclick="selectListColor('{{ $presetColor }}')"
                        data-color="{{ $presetColor }}"
                        class="list-color-swatch"
                        style="width:28px; height:28px; border-radius:50%; background:{{ $presetColor }}; border:3px solid {{ $currentColor === $presetColor ? '#1c1c1e' : 'transparent' }}; outline:3px solid {{ $currentColor === $presetColor ? $presetColor : 'transparent' }}; outline-offset:2px; cursor:pointer; transition:border-color .15s, outline-color .15s; padding:0;">
                </button>
            @endforeach
        </div>
        @error('color')
            <p class="reminder-error-msg">{{ $message }}</p>
        @enderror
    </div>

    {{-- Lista por defecto --}}
    <div class="reminder-checkbox-row">
        <input type="hidden" name="is_default" value="0">
        <input type="checkbox" name="is_default" value="1" id="is_default"
               {{ old('is_default', $list->is_default ?? false) ? 'checked' : '' }}
               class="reminder-checkbox-input">
        <label for="is_default" class="reminder-checkbox-label">Marcar como lista por defecto</label>
    </div>

</div>

<script>
function selectListColor(color) {
    document.getElementById('list-color-input').value = color;
    var fgColor = getComputedStyle(document.documentElement).getPropertyValue('--fg').trim() || '#1c1c1e';
    document.querySelectorAll('.list-color-swatch').forEach(function(btn) {
        if (btn.getAttribute('data-color') === color) {
            btn.style.border = '3px solid ' + fgColor;
            btn.style.outline = '3px solid ' + color;
        } else {
            btn.style.border = '3px solid transparent';
            btn.style.outline = '3px solid transparent';
        }
    });
}
</script>
