@extends('dashboard.features.reminders.app')

@section('reminders-content')
<div style="max-width:640px; margin:0 auto;">

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
        <h1 style="font-size:28px; font-weight:700; color:#1c1c1e; margin:0;">Etiquetas</h1>
        <a href="{{ route('reminders.tags.create') }}"
           style="background:#007aff; color:#fff; padding:10px 18px; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600;">
            + Nueva etiqueta
        </a>
    </div>

    @if($tags->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#8e8e93;">
            <div style="font-size:48px; margin-bottom:12px;">🏷️</div>
            <p style="font-size:18px; font-weight:600; color:#1c1c1e; margin-bottom:8px;">Sin etiquetas</p>
            <p style="font-size:14px; margin-bottom:20px;">Las etiquetas te ayudan a categorizar tus recordatorios.</p>
            <a href="{{ route('reminders.tags.create') }}"
               style="background:#007aff; color:#fff; padding:10px 20px; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600;">
                Crear etiqueta
            </a>
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:8px;">
            @foreach($tags as $tag)
                <div style="background:#fff; border:1px solid #e5e5ea; border-radius:12px; padding:14px 18px; display:flex; align-items:center; gap:12px;">
                    <span style="width:16px; height:16px; border-radius:50%; background:{{ $tag->color ?? '#8e8e93' }}; flex-shrink:0;"></span>

                    <div style="flex:1;">
                        <span style="font-size:15px; font-weight:600; color:#1c1c1e;">{{ $tag->name }}</span>
                        <span style="font-size:12px; color:#8e8e93; margin-left:8px;">{{ $tag->reminders_count }} recordatorio(s)</span>
                    </div>

                    <div style="display:flex; gap:8px;">
                        <a href="{{ route('reminders.tags.show', $tag) }}"
                           style="background:#f2f2f7; color:#007aff; padding:7px 12px; border-radius:8px; text-decoration:none; font-size:13px;">Ver</a>
                        <a href="{{ route('reminders.tags.edit', $tag) }}"
                           style="background:#f2f2f7; color:#1c1c1e; padding:7px 12px; border-radius:8px; text-decoration:none; font-size:13px;">Editar</a>
                        <form action="{{ route('reminders.tags.destroy', $tag) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar la etiqueta «{{ $tag->name }}»?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    style="background:#fee2e2; color:#dc2626; padding:7px 12px; border-radius:8px; border:none; cursor:pointer; font-size:13px;">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
