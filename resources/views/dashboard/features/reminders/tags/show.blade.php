@extends('dashboard.features.reminders.app')

@section('reminders-content')
<div style="max-width:700px; margin:0 auto;">

    <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
        <a href="{{ route('reminders.tags.index') }}" style="color:#007aff; font-size:14px; text-decoration:none;">← Etiquetas</a>
        <div style="display:flex; align-items:center; gap:10px;">
            <span style="width:14px; height:14px; border-radius:50%; background:{{ $tag->color ?? '#8e8e93' }};"></span>
            <h1 style="font-size:24px; font-weight:700; color:#1c1c1e; margin:0;">{{ $tag->name }}</h1>
        </div>
    </div>

    @if($reminders->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#8e8e93;">
            <div style="font-size:48px; margin-bottom:12px;">🏷️</div>
            <p style="font-size:16px; color:#1c1c1e; margin-bottom:8px;">Sin recordatorios con esta etiqueta</p>
        </div>
    @else
        <div style="display:flex; flex-direction:column; gap:6px;">
            @foreach($reminders as $reminder)
                @include('dashboard.features.reminders.reminders._reminder-card', ['reminder' => $reminder])
            @endforeach
        </div>
        <div style="margin-top:20px;">{{ $reminders->links() }}</div>
    @endif
</div>
@endsection
