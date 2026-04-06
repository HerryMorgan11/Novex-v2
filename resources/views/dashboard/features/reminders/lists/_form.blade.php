{{-- Formulario reutilizable para crear/editar listas --}}
<div style="display:flex; flex-direction:column; gap:18px;">

    {{-- Nombre --}}
    <div>
        <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">
            Nombre <span style="color:#ff3b30;">*</span>
        </label>
        <input type="text" name="name" value="{{ old('name', $list->name ?? '') }}"
               placeholder="Mi lista"
               style="width:100%; padding:10px 14px; border:1.5px solid {{ $errors->has('name') ? '#ff3b30' : '#e5e5ea' }}; border-radius:10px; font-size:15px; outline:none; box-sizing:border-box;">
        @error('name')
            <p style="color:#ff3b30; font-size:12px; margin-top:4px;">{{ $message }}</p>
        @enderror
    </div>

    {{-- Color e icono en fila --}}
    <div style="display:flex; gap:16px;">
        <div style="flex:1;">
            <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">Color</label>
            <input type="color" name="color" value="{{ old('color', $list->color ?? '#007aff') }}"
                   style="width:60px; height:40px; border:1.5px solid #e5e5ea; border-radius:10px; cursor:pointer; padding:2px;">
            @error('color')
                <p style="color:#ff3b30; font-size:12px; margin-top:4px;">{{ $message }}</p>
            @enderror
        </div>

        <div style="flex:1;">
            <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">Icono</label>
            <select name="icon"
                    style="width:100%; padding:10px 14px; border:1.5px solid #e5e5ea; border-radius:10px; font-size:15px; background:#fff; outline:none;">
                <option value="">Sin icono</option>
                @foreach(['⭐ star', '🔔 bell', '🚩 flag', '✅ checkmark', '🏠 home', '💼 briefcase', '🛒 cart', '📚 books', '💡 bulb', '❤️ heart'] as $iconOption)
                    @php [$emoji, $val] = explode(' ', $iconOption, 2); @endphp
                    <option value="{{ $val }}" {{ old('icon', $list->icon ?? '') === $val ? 'selected' : '' }}>
                        {{ $iconOption }}
                    </option>
                @endforeach
            </select>
        </div>
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
