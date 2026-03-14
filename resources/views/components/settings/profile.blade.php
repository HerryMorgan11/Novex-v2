<?php

use Livewire\Component;

return new class extends Component
{
    
};

?>

<div x-data="{ section: 'perfil' }" x-cloak class="settings-container">
    <div class="settings-wrapper">
        <div class="settings-layout">
            <!-- Sidebar -->
            <div class="settings-sidebar">
                <nav class="settings-nav">
                    <button
                        @click="section = 'perfil'"
                        :class="{ 'active': section === 'perfil' }"
                    >
                        <iconify-icon icon="gg:profile" width="16" height="16"></iconify-icon>
                        Perfil
                    </button>
                    <button
                        @click="section = 'seguridad'"
                        :class="{ 'active': section === 'seguridad' }"
                    >
                        <iconify-icon icon="gg:lock" width="16" height="16"></iconify-icon>
                        Seguridad
                    </button>
                </nav>
            </div>

            <!-- Contenido principal -->
            <div class="settings-content">
                <div x-show="section === 'perfil'">
                    @include('dashboard.features.settings.ui.profile-content')
                </div>

                <div x-show="section === 'seguridad'">
                    @include('dashboard.features.settings.ui.security')
                </div>
            </div>
        </div>
    </div>
</div>
