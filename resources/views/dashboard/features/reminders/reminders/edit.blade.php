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
                @if($reminder->status === 'active')
                    <form action="{{ route('reminders.archive', $reminder) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-archive">
                            📦 Archivar
                        </button>
                    </form>
                @else
                    <form action="{{ route('reminders.unarchive', $reminder) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-restore">
                            📤 Restaurar al activo
                        </button>
                    </form>
                @endif

                <form action="{{ route('reminders.destroy', $reminder) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar este recordatorio? Podrás restaurarlo después.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete-reminder">
                        🗑 Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
