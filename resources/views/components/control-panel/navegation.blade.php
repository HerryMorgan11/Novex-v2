<?php

use Livewire\Component;
use App\Models\User;

return new class extends Component
{
    public $section = "home";
    public $users = [];

    public function mount()
    {
        $this->users = User::all();
    }

    public function changeSection($section)
    {
        $this->section = $section;
    }
};

?>

<div x-data="{ section: 'home' }" x-cloak class="panel-container">
    <div class="panel-wrapper">
        <div class="panel-layout">
            <!-- SIDEBAR -->
            <div class="panel-sidebar">
                <nav class="panel-nav">
                    <button
                        @click="section = 'home'"
                        :class="{ 'active': section === 'home' }"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Dashboard
                    </button>
                    <button
                        @click="section = 'users'"
                        :class="{ 'active': section === 'users' }"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Usuarios
                    </button>
                    <button
                        @click="section = 'company'"
                        :class="{ 'active': section === 'company' }"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
                        Empresa
                    </button>
                    <button
                        @click="section = 'modules'"
                        :class="{ 'active': section === 'modules' }"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                        Módulos
                    </button>
                </nav>
            </div>

            <!-- CONTENT -->
            <div class="panel-content">
                <div x-show="section === 'home'">
                    @include('dashboard.features.control-panel.ui.home')
                </div>

                <div x-show="section === 'users'">
                    @include('dashboard.features.control-panel.ui.users')
                </div>

                <div x-show="section === 'company'">
                    @include('dashboard.features.control-panel.ui.company')
                </div>

                <div x-show="section === 'modules'">
                    @include('dashboard.features.control-panel.ui.modules')
                </div>
            </div>
        </div>
    </div>
</div>