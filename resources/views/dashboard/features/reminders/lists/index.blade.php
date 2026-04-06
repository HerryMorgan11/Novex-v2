@extends('dashboard.features.reminders.app')

@section('reminders-content')
<div style="max-width:700px; margin:0 auto;">

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
        <h1 style="font-size:28px; font-weight:700; color:#1c1c1e; margin:0;">Mis Listas</h1>
        <a href="{{ route('reminders.lists.create') }}"
           style="background:#007aff; color:#fff; padding:10px 18px; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600;">
            + Nueva lista
        </a>
    </div>

    @if($lists->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#8e8e93;">
            <div style="font-size:48px; margin-bottom:12px;">📋</div>
            <p style="font-size:18px; font-weight:600; color:#1c1c1e; margin-bottom:8px;">Sin listas todavía</p>
            <p style="font-size:14px; margin-bottom:20px;">Crea tu primera lista para organizar tus recordatorios.</p>
            <a href="{{ route('reminders.lists.create') }}"
               style="background:#007aff; color:#fff; padding:10px 20px; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600;">
                Crear lista
            </a>
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:8px;">
            @foreach($lists as $list)
                <div style="background:#fff; border:1px solid #e5e5ea; border-radius:12px; padding:14px 18px; display:flex; align-items:center; gap:12px;">
                    <span style="width:16px; height:16px; border-radius:50%; background:{{ $list->color ?? '#007aff' }}; flex-shrink:0;"></span>

                    <div style="flex:1;">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <span style="font-size:15px; font-weight:600; color:#1c1c1e;">{{ $list->name }}</span>
                            @if($list->is_default)
                                <span style="background:#e5e5ea; color:#8e8e93; padding:2px 8px; border-radius:10px; font-size:11px;">Por defecto</span>
                            @endif
                        </div>
                        <div style="font-size:12px; color:#8e8e93; margin-top:2px;">
                            {{ $list->pendingRemindersCount() }} pendiente(s) · {{ $list->completedRemindersCount() }} completado(s)
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:8px;">
                        <a href="{{ route('reminders.index', ['list' => $list->id]) }}"
                           style="background:#f2f2f7; color:#007aff; padding:7px 12px; border-radius:8px; text-decoration:none; font-size:13px;">Ver</a>
                        <a href="{{ route('reminders.lists.edit', $list) }}"
                           style="background:#f2f2f7; color:#1c1c1e; padding:7px 12px; border-radius:8px; text-decoration:none; font-size:13px;">Editar</a>
                        @cannot('delete', $list)
                        @else
                            <form action="{{ route('reminders.lists.destroy', $list) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta lista? Los recordatorios se conservarán.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="background:#fee2e2; color:#dc2626; padding:7px 12px; border-radius:8px; border:none; cursor:pointer; font-size:13px;">Eliminar</button>
                            </form>
                        @endcannot
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
