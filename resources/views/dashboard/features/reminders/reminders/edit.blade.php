@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/reminders/reminders.css'])
@endpush

@section('content')
<div class="reminder-form-page">

    <div class="reminder-form-breadcrumb">
        <a href="{{ route('reminders.show', $reminder) }}" class="reminder-form-back-link">← Volver</a>
        <h1 class="reminder-form-page-title">Editar Recordatorio</h1>
    </div>

    <div class="reminder-form-card">
        <form action="{{ route('reminders.update', $reminder) }}" method="POST">
            @csrf
            @method('PUT')
            @include('dashboard.features.reminders.reminders._form')

            <div class="reminder-form-actions">
                <button type="submit" class="reminder-form-submit">
                    Guardar cambios
                </button>
                <a href="{{ route('reminders.show', $reminder) }}" class="reminder-form-cancel">
                    Cancelar
                </a>
            </div>
        </form>

        <div class="reminder-danger-zone">
            <p class="reminder-danger-label">Zona de peligro</p>
            <div class="reminder-danger-actions">
                <button type="button" class="btn-delete-reminder" onclick="openDeleteReminderDialog()">
                    Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<div id="dialogDeleteReminder" class="confirm-dialog-overlay" onclick="if(event.target===this)closeDeleteReminderDialog()">
    <div class="confirm-dialog">
        <div class="confirm-dialog-icon">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M10 2a8 8 0 100 16A8 8 0 0010 2zm0 4v4m0 4h.01" stroke="#ff3b30" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h3 class="confirm-dialog-title">Eliminar recordatorio</h3>
        <p class="confirm-dialog-desc">¿Seguro que deseas eliminar <strong>{{ $reminder->title }}</strong>?</p>
        <div class="confirm-dialog-footer">
            <button type="button" class="confirm-dialog-cancel" onclick="closeDeleteReminderDialog()">Cancelar</button>
            <form action="{{ route('reminders.destroy', $reminder) }}" method="POST" style="flex:1;">
                @csrf
                @method('DELETE')
                <button type="submit" class="confirm-dialog-confirm" style="width:100%;">Eliminar</button>
            </form>
        </div>
    </div>
</div>

<script>
function openDeleteReminderDialog() {
    document.getElementById('dialogDeleteReminder').classList.add('open');
}

function closeDeleteReminderDialog() {
    document.getElementById('dialogDeleteReminder').classList.remove('open');
}
</script>
@endsection
