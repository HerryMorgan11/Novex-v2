@extends('dashboard.app.dashboard')

@section('content')
<div class="reminders-wrapper" style="display:flex; min-height:100%; gap:0;">

    {{-- Sidebar de Recordatorios --}}
    <aside class="reminders-sidebar" style="width:240px; min-width:240px; background:#f5f5f7; border-right:1px solid #e5e5ea; padding:20px 12px; overflow-y:auto;">

        {{-- Filtros rápidos --}}
        <div style="margin-bottom:24px;">
            <p style="font-size:11px; font-weight:600; color:#8e8e93; text-transform:uppercase; letter-spacing:.05em; margin-bottom:8px; padding:0 8px;">Mis recordatorios</p>

            <a href="{{ route('reminders.index', ['filter' => 'pending']) }}"
               class="reminders-nav-link"
               style="display:flex; align-items:center; gap:8px; padding:7px 10px; border-radius:8px; text-decoration:none; color:#1c1c1e; font-size:14px; font-weight:500; {{ request('filter', 'pending') === 'pending' && !request('list') ? 'background:#e5e5ea;' : '' }}">
                <span style="font-size:16px;">⏰</span> Pendientes
            </a>

            <a href="{{ route('reminders.index', ['filter' => 'today']) }}"
               style="display:flex; align-items:center; gap:8px; padding:7px 10px; border-radius:8px; text-decoration:none; color:#1c1c1e; font-size:14px; font-weight:500; {{ request('filter') === 'today' ? 'background:#e5e5ea;' : '' }}">
                <span style="font-size:16px;">📅</span> Hoy
            </a>

            <a href="{{ route('reminders.index', ['filter' => 'overdue']) }}"
               style="display:flex; align-items:center; gap:8px; padding:7px 10px; border-radius:8px; text-decoration:none; color:#ff3b30; font-size:14px; font-weight:500; {{ request('filter') === 'overdue' ? 'background:#ffe5e3;' : '' }}">
                <span style="font-size:16px;">🔴</span> Vencidos
            </a>

            <a href="{{ route('reminders.index', ['filter' => 'completed']) }}"
               style="display:flex; align-items:center; gap:8px; padding:7px 10px; border-radius:8px; text-decoration:none; color:#1c1c1e; font-size:14px; font-weight:500; {{ request('filter') === 'completed' ? 'background:#e5e5ea;' : '' }}">
                <span style="font-size:16px;">✅</span> Completados
            </a>

            <a href="{{ route('reminders.index', ['filter' => 'all']) }}"
               style="display:flex; align-items:center; gap:8px; padding:7px 10px; border-radius:8px; text-decoration:none; color:#1c1c1e; font-size:14px; font-weight:500; {{ request('filter') === 'all' ? 'background:#e5e5ea;' : '' }}">
                <span style="font-size:16px;">📋</span> Todos
            </a>
        </div>

        {{-- Listas --}}
        @php
            $sidebarLists = \App\Models\ReminderList::forUser(auth()->user())->ordered()->get();
        @endphp
        @if($sidebarLists->isNotEmpty())
            <div style="margin-bottom:24px;">
                <div style="display:flex; align-items:center; justify-content:space-between; padding:0 8px; margin-bottom:8px;">
                    <p style="font-size:11px; font-weight:600; color:#8e8e93; text-transform:uppercase; letter-spacing:.05em; margin:0;">Mis listas</p>
                    <a href="{{ route('reminders.lists.create') }}" style="color:#007aff; font-size:18px; line-height:1; text-decoration:none;">+</a>
                </div>

                @foreach($sidebarLists as $sidebarList)
                    <a href="{{ route('reminders.index', ['list' => $sidebarList->id]) }}"
                       style="display:flex; align-items:center; gap:8px; padding:7px 10px; border-radius:8px; text-decoration:none; color:#1c1c1e; font-size:14px; {{ request('list') == $sidebarList->id ? 'background:#e5e5ea; font-weight:500;' : '' }}">
                        <span style="width:10px; height:10px; border-radius:50%; background:{{ $sidebarList->color ?? '#007aff' }}; flex-shrink:0;"></span>
                        <span style="flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $sidebarList->name }}</span>
                        @php $count = $sidebarList->pendingRemindersCount(); @endphp
                        @if($count > 0)
                            <span style="background:#007aff; color:#fff; border-radius:10px; padding:1px 6px; font-size:11px; font-weight:600;">{{ $count }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Etiquetas --}}
        <div>
            <div style="display:flex; align-items:center; justify-content:space-between; padding:0 8px; margin-bottom:8px;">
                <p style="font-size:11px; font-weight:600; color:#8e8e93; text-transform:uppercase; letter-spacing:.05em; margin:0;">Etiquetas</p>
                <a href="{{ route('reminders.tags.create') }}" style="color:#007aff; font-size:18px; line-height:1; text-decoration:none;">+</a>
            </div>
            @php
                $sidebarTags = \App\Models\Tag::forUser(auth()->user())->ordered()->get();
            @endphp
            @foreach($sidebarTags as $sidebarTag)
                <a href="{{ route('reminders.index', ['tag' => $sidebarTag->id]) }}"
                   style="display:flex; align-items:center; gap:8px; padding:6px 10px; border-radius:8px; text-decoration:none; color:#1c1c1e; font-size:13px; {{ request('tag') == $sidebarTag->id ? 'background:#e5e5ea;' : '' }}">
                    <span style="width:8px; height:8px; border-radius:50%; background:{{ $sidebarTag->color ?? '#8e8e93' }}; flex-shrink:0;"></span>
                    {{ $sidebarTag->name }}
                </a>
            @endforeach

            @if($sidebarTags->isEmpty())
                <p style="font-size:12px; color:#8e8e93; padding:4px 10px;">Sin etiquetas</p>
            @endif
        </div>

        {{-- Acciones de gestión --}}
        <div style="margin-top:auto; padding-top:16px; border-top:1px solid #e5e5ea;">
            <a href="{{ route('reminders.lists.index') }}" style="display:block; padding:6px 10px; font-size:13px; color:#007aff; text-decoration:none;">Gestionar listas</a>
            <a href="{{ route('reminders.tags.index') }}" style="display:block; padding:6px 10px; font-size:13px; color:#007aff; text-decoration:none;">Gestionar etiquetas</a>
        </div>
    </aside>

    {{-- Contenido Principal --}}
    <div class="reminders-content" style="flex:1; overflow-y:auto; padding:24px 28px;">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div style="background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; padding:12px 16px; border-radius:10px; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; padding:12px 16px; border-radius:10px; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                <span>❌</span> {{ session('error') }}
            </div>
        @endif

        @yield('reminders-content')
    </div>
</div>
@endsection
