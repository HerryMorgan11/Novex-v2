@extends('dashboard.app.dashboard')

@section('content')
<div style="max-width:680px; margin:0 auto; padding:20px;">

    <div style="display:flex; align-items:center; gap:12px; margin-bottom:28px;">
        <a href="{{ route('reminders.show', $reminder) }}" style="color:#007aff; font-size:14px; text-decoration:none;">← Volver</a>
        <h1 style="font-size:24px; font-weight:700; color:#1c1c1e; margin:0;">Editar Recordatorio</h1>
    </div>

    <div style="background:#fff; border:1px solid #e5e5ea; border-radius:16px; padding:28px;">
        <form action="{{ route('reminders.update', $reminder) }}" method="POST">
            @csrf
            @method('PUT')
            @include('dashboard.features.reminders.reminders._form')

            <div style="display:flex; gap:10px; margin-top:24px; padding-top:20px; border-top:1px solid #e5e5ea;">
                <button type="submit"
                        style="flex:1; background:#007aff; color:#fff; padding:12px; border-radius:10px; border:none; font-size:15px; font-weight:600; cursor:pointer;">
                    Guardar cambios
                </button>
                <a href="{{ route('reminders.show', $reminder) }}"
                   style="flex:1; text-align:center; background:#f2f2f7; color:#1c1c1e; padding:12px; border-radius:10px; text-decoration:none; font-size:15px; font-weight:600;">
                    Cancelar
                </a>
            </div>
        </form>

        <div style="margin-top:28px; padding-top:20px; border-top:1px solid #fee2e2;">
            <p style="font-size:12px; font-weight:600; color:#dc2626; text-transform:uppercase; letter-spacing:.05em; margin-bottom:12px;">Zona de peligro</p>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                @if($reminder->status === 'active')
                    <form action="{{ route('reminders.archive', $reminder) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" style="background:#fff7ed; color:#c2410c; border:1px solid #fdba74; padding:10px 16px; border-radius:10px; font-size:13px; cursor:pointer;">
                            📦 Archivar
                        </button>
                    </form>
                @else
                    <form action="{{ route('reminders.unarchive', $reminder) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" style="background:#f0fdf4; color:#15803d; border:1px solid #86efac; padding:10px 16px; border-radius:10px; font-size:13px; cursor:pointer;">
                            📤 Restaurar al activo
                        </button>
                    </form>
                @endif

                <form action="{{ route('reminders.destroy', $reminder) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar este recordatorio? Podrás restaurarlo después.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; padding:10px 16px; border-radius:10px; font-size:13px; cursor:pointer;">
                        🗑 Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
