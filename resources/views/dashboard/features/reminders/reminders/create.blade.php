@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/reminders/reminders.css'])
@endpush

@section('content')
<div class="reminder-form-page">

    <div class="reminder-form-breadcrumb">
        <a href="{{ route('reminders.index') }}" class="reminder-form-back-link">← Volver</a>
        <h1 class="reminder-form-page-title">Nuevo Recordatorio</h1>
    </div>

    <div class="reminder-form-card">
        <form action="{{ route('reminders.store') }}" method="POST">
            @csrf
            @include('dashboard.features.reminders.reminders._form')

            <div class="reminder-form-actions">
                <button type="submit" class="reminder-form-submit">
                    Crear recordatorio
                </button>
                <a href="{{ route('reminders.index') }}" class="reminder-form-cancel">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
