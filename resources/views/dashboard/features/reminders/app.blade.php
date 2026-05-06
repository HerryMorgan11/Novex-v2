@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/reminders/reminders.css'])
@endpush

@section('content')
<div class="reminders-wrapper">

    {{-- Sidebar de Recordatorios --}}
    <aside class="reminders-sidebar">

        {{-- Filtros rápidos --}}
        <div class="reminders-nav-section">
            <p class="reminders-nav-section-label">Mis recordatorios</p>

            <a href="{{ route('reminders.index', ['filter' => 'pending']) }}"
               class="reminders-nav-link {{ request('filter', 'pending') === 'pending' && !request('list') ? 'active' : '' }}">
                <span class="rem-nav-icon">⏰</span> Pendientes
            </a>

            <a href="{{ route('reminders.index', ['filter' => 'today']) }}"
               class="reminders-nav-link {{ request('filter') === 'today' ? 'active' : '' }}">
                <span class="rem-nav-icon">📅</span> Hoy
            </a>

            <a href="{{ route('reminders.index', ['filter' => 'overdue']) }}"
               class="reminders-nav-link overdue {{ request('filter') === 'overdue' ? 'active' : '' }}">
                <span class="rem-nav-icon">🔴</span> Vencidos
            </a>

            <a href="{{ route('reminders.index', ['filter' => 'completed']) }}"
               class="reminders-nav-link {{ request('filter') === 'completed' ? 'active' : '' }}">
                <span class="rem-nav-icon">✅</span> Completados
            </a>

            <a href="{{ route('reminders.index', ['filter' => 'all']) }}"
               class="reminders-nav-link {{ request('filter') === 'all' ? 'active' : '' }}">
                <span class="rem-nav-icon">📋</span> Todos
            </a>
        </div>

        {{-- Listas --}}
        @php
            $sidebarLists = \App\Models\ReminderList::forUser(auth()->user())->ordered()->get();
        @endphp
        @if($sidebarLists->isNotEmpty())
            <div class="reminders-nav-section">
                <div class="reminders-sidebar-lists-header">
                    <p class="reminders-nav-section-label">Mis listas</p>
                    <a href="{{ route('reminders.lists.create') }}" class="reminders-sidebar-add-list">+</a>
                </div>

                @foreach($sidebarLists as $sidebarList)
                    <a href="{{ route('reminders.index', ['list' => $sidebarList->id]) }}"
                       class="reminders-list-link {{ request('list') == $sidebarList->id ? 'active' : '' }}">
                        <span class="reminders-list-dot" style="background:{{ $sidebarList->color ?? '#007aff' }};"></span>
                        <span class="reminders-list-name">{{ $sidebarList->name }}</span>
                        @php $count = $sidebarList->pendingRemindersCount(); @endphp
                        @if($count > 0)
                            <span class="reminders-list-badge">{{ $count }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endif

    </aside>

    {{-- Contenido Principal --}}
    <div class="reminders-content">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="reminders-flash-success">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="reminders-flash-error">
                <span>❌</span> {{ session('error') }}
            </div>
        @endif

        @yield('reminders-content')
    </div>
</div>
@endsection
