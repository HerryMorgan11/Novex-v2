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
        </div>

        <div class="settings-layout">
            <!-- Sidebar -->
            <div class="settings-sidebar">
                <nav class="settings-nav">
                    <button 
                        wire:click="changeSection('perfil')"
                        @class(['active' => $section === 'perfil'])
                    >
                        Perfil
                    </button>
                    <button 
                        wire:click="changeSection('seguridad')"
                        @class(['active' => $section === 'seguridad'])
                    >
                        Seguridad
                    </button>
                    <button 
                        wire:click="changeSection('notificaciones')"
                        @class(['active' => $section === 'notificaciones'])
                    >
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
