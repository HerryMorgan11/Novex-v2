@extends('dashboard.app.dashboard')

@push('styles')
    @vite('resources/css/dashboard/features/control-panel/control-panel.css')
@endpush

@section('content')
    <div class="panel-container">
        <div class="panel-wrapper">
            <div class="panel-layout">
                <div class="panel-sidebar">
                    <nav class="panel-nav">
                        <button data-panel-section="home" type="button">
                            <iconify-icon icon="lucide:layout-grid" width="18" height="18"></iconify-icon>
                            Dashboard
                        </button>
                        <button data-panel-section="users" type="button">
                            <iconify-icon icon="formkit:people" width="18" height="18"></iconify-icon>
                            Usuarios
                        </button>
                        <button data-panel-section="company" type="button">
                            <iconify-icon icon="lucide:building-2" width="18" height="18"></iconify-icon>
                            Empresa
                        </button>
                        <button data-panel-section="modules" type="button">
                            <iconify-icon icon="lucide:blocks" width="18" height="18"></iconify-icon>
                            Módulos
                        </button>
                    </nav>
                </div>

                <div class="panel-content">
                    <div data-panel-content="home">
                        @include('dashboard.features.control-panel.ui.home')
                    </div>

                    <div data-panel-content="users">
                        @include('dashboard.features.control-panel.ui.users', ['users' => $users ?? []])
                    </div>

                    <div data-panel-content="company">
                        @include('dashboard.features.control-panel.ui.company')
                    </div>

                    <div data-panel-content="modules">
                        @include('dashboard.features.control-panel.ui.modules')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/controlPanel/navigation.js')
    @vite('resources/js/controlPanel/curl-generator.js')
@endpush
