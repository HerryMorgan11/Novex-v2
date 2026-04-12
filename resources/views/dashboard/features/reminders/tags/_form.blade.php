{{-- Formulario reutilizable para crear/editar etiquetas --}}
<div style="display:flex; flex-direction:column; gap:18px;">

    <div>
        <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">
            Nombre <span style="color:#ff3b30;">*</span>
        </label>
        <input type="text" name="name" value="{{ old('name', $tag->name ?? '') }}"
               placeholder="Trabajo, Personal, Urgente..."
               style="width:100%; padding:11px 14px; border:1.5px solid {{ $errors->has('name') ? '#ff3b30' : '#e5e5ea' }}; border-radius:10px; font-size:15px; outline:none; box-sizing:border-box;">
        @error('name')
            <p style="color:#ff3b30; font-size:12px; margin-top:4px;">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label style="display:block; font-size:13px; font-weight:600; color:#1c1c1e; margin-bottom:6px;">Color</label>
        <div style="display:flex; align-items:center; gap:12px;">
            <input type="color" name="color" value="{{ old('color', $tag->color ?? '#007aff') }}"
                   style="width:50px; height:40px; border:1.5px solid #e5e5ea; border-radius:10px; cursor:pointer; padding:2px;">
            <p style="font-size:13px; color:#8e8e93; margin:0;">Se usará para identificar visualmente la etiqueta.</p>
        </div>
    </div>

</div>
