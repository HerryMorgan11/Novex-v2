@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/reminders/reminders.css'])
@endpush

@section('content')
<div class="reminder-form-page-sm">

    <div class="reminder-form-breadcrumb">
        <a href="{{ route('reminders.lists.index') }}" class="reminder-form-back-link">← Volver</a>
        <h1 class="reminder-form-page-title">Nueva Lista</h1>
    </div>

    <div class="reminder-form-card-sm">
        <form action="{{ route('reminders.lists.store') }}" method="POST">
            @csrf
            @include('dashboard.features.reminders.lists._form')

            <div class="reminder-form-actions">
                <button type="submit" class="reminder-form-submit">
                    Crear lista
                </button>
                <a href="{{ route('reminders.lists.index') }}" class="reminder-form-cancel">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
