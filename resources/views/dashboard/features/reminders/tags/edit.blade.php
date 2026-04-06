@extends('dashboard.app.dashboard')

@section('content')
<div style="max-width:460px; margin:0 auto; padding:20px;">

    <div style="display:flex; align-items:center; gap:12px; margin-bottom:28px;">
        <a href="{{ route('reminders.tags.index') }}" style="color:#007aff; font-size:14px; text-decoration:none;">← Volver</a>
        <h1 style="font-size:24px; font-weight:700; color:#1c1c1e; margin:0;">Editar Etiqueta</h1>
    </div>

    <div style="background:#fff; border:1px solid #e5e5ea; border-radius:16px; padding:24px;">
        <form action="{{ route('reminders.tags.update', $tag) }}" method="POST">
            @csrf
            @method('PUT')
            @include('dashboard.features.reminders.tags._form')

            <div style="display:flex; gap:10px; margin-top:24px; padding-top:20px; border-top:1px solid #e5e5ea;">
                <button type="submit"
                        style="flex:1; background:#007aff; color:#fff; padding:12px; border-radius:10px; border:none; font-size:15px; font-weight:600; cursor:pointer;">
                    Guardar cambios
                </button>
                <a href="{{ route('reminders.tags.index') }}"
                   style="flex:1; text-align:center; background:#f2f2f7; color:#1c1c1e; padding:12px; border-radius:10px; text-decoration:none; font-size:15px; font-weight:600;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
