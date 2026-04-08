{{-- Formulario reutilizable para crear/editar listas --}}
@php
    $presetColors = ['#007aff','#34c759','#ff3b30','#ff9500','#ffcc00','#af52de','#ff2d55','#5ac8fa','#30b0c7','#a2845e','#8e8e93','#1c1c1e'];
    $currentColor = old('color', $list->color ?? '#007aff');
@endphp
<div style="display:flex; flex-direction:column; gap:18px;">

    {{-- Nombre --}}
    <div>
        <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">
            Nombre <span style="color:#ff3b30;">*</span>
        </label>
        <input type="text" name="name" id="list-name-input"
               value="{{ old('name', $list->name ?? '') }}"
               placeholder="Nombre de la lista"
               autocomplete="off"
               style="width:100%; padding:10px 14px; border:1.5px solid {{ $errors->has('name') ? '#ff3b30' : '#e5e5ea' }}; border-radius:10px; font-size:15px; outline:none; box-sizing:border-box;">
        @error('name')
            <p style="color:#ff3b30; font-size:12px; margin-top:4px;">{{ $message }}</p>
        @enderror
    </div>

    {{-- Color --}}
    <div>
        <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:10px;">Color</label>
        <input type="hidden" name="color" id="list-color-input" value="{{ $currentColor }}">
        <div style="display:flex; flex-wrap:wrap; gap:10px;">
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
            <p style="color:#ff3b30; font-size:12px; margin-top:4px;">{{ $message }}</p>
        @enderror
    </div>

    {{-- Lista por defecto --}}
    <div style="display:flex; align-items:center; gap:10px;">
        <input type="hidden" name="is_default" value="0">
        <input type="checkbox" name="is_default" value="1" id="is_default"
               {{ old('is_default', $list->is_default ?? false) ? 'checked' : '' }}
               style="width:18px; height:18px; accent-color:#007aff; cursor:pointer;">
        <label for="is_default" style="font-size:14px; color:#1c1c1e; cursor:pointer;">Marcar como lista por defecto</label>
    </div>

</div>

<script>
function selectListColor(color) {
    document.getElementById('list-color-input').value = color;
    document.querySelectorAll('.list-color-swatch').forEach(function(btn) {
        if (btn.getAttribute('data-color') === color) {
            btn.style.border = '3px solid #1c1c1e';
            btn.style.outline = '3px solid ' + color;
        } else {
            btn.style.border = '3px solid transparent';
            btn.style.outline = '3px solid transparent';
        }
    });
}
</script>
