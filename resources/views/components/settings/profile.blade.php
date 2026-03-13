<?php

use Livewire\Component;

return new class extends Component
{
    public $section = "perfil";

    public function changeSection($section)
    {
        $this->section = $section;
    }
};

?>


<div class="settings-container">
    <div class="settings-wrapper">
        <div class="settings-header">
            <h1>Configuración</h1>
            <p>Administra la configuración de tu cuenta y preferencias.</p>
        </div>

        <div class="settings-layout">
            <!-- Sidebar -->
            <div class="settings-sidebar">
                <nav class="settings-nav">
                    <button 
                        wire:click="changeSection('perfil')"
                        @class(['active' => $section === 'perfil'])
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Perfil
                    </button>
                    <button 
                        wire:click="changeSection('seguridad')"
                        @class(['active' => $section === 'seguridad'])
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Seguridad
                    </button>
                    <button 
                        wire:click="changeSection('notificaciones')"
                        @class(['active' => $section === 'notificaciones'])
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        Notificaciones
                    </button>
                </nav>
            </div>

            <!-- Contenido principal -->
            <div class="settings-content">
                <!-- Perfil Section -->
                @if ($section === 'perfil')
                    <div class="settings-section">
                        <h2>Mi Perfil</h2>
                        
                        <div class="settings-field">
                            <label>Nombre</label>
                            <p>{{ auth()->user()->name ?? 'Sin nombre' }}</p>
                        </div>

                        <div class="settings-field">
                            <label>Email</label>
                            <p>{{ auth()->user()->email }}</p>
                        </div>

                        <div style="padding-top: 0.5rem;">
                            <button class="settings-btn settings-btn-primary">
                                Editar Perfil
                            </button>
                        </div>
                    </div>

                <!-- Seguridad Section -->
                @elseif ($section === 'seguridad')
                    <div class="settings-section">
                        <h2>Seguridad</h2>
                        
                        <div class="settings-security-item">
                            <h3>Contraseña</h3>
                            <p>Actualiza tu contraseña regularmente para mantener tu cuenta segura.</p>
                            <button class="settings-btn settings-btn-secondary">
                                Cambiar Contraseña
                            </button>
                        </div>

                        <div class="settings-security-item">
                            <h3>Autenticación de dos factores</h3>
                            <p>Protege tu cuenta con una capa adicional de seguridad.</p>
                            <button class="settings-btn settings-btn-secondary">
                                Configurar 2FA
                            </button>
                        </div>
                    </div>

                <!-- Notificaciones Section -->
                @elseif ($section === 'notificaciones')
                    <div class="settings-section">
                        <h2>Notificaciones</h2>
                        
                        <div class="settings-notification-item">
                            <div>
                                <h3>Actualizaciones importantes</h3>
                                <p>Cambios críticos y alertas de seguridad</p>
                            </div>
                            <input type="checkbox" checked>
                        </div>

                        <div class="settings-notification-item">
                            <div>
                                <h3>Nuevas funciones</h3>
                                <p>Entérate de nuevas características y mejoras</p>
                            </div>
                            <input type="checkbox">
                        </div>

                        <div class="settings-notification-item">
                            <div>
                                <h3>Actividad de cuenta</h3>
                                <p>Notificaciones sobre cambios en tu cuenta</p>
                            </div>
                            <input type="checkbox" checked>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
