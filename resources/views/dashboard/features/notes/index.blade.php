@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/notes/notes.css'])
@endpush

@section('content')
<div class="notes-container">
    <div class="notes-header">
        <h1>Mis Notas</h1>
        <a href="{{ route('dashboard.features.notes.create') }}" class="btn-create-note">
            <iconify-icon icon="lucide:plus"></iconify-icon>
            Nueva Nota
        </a>
    </div>

    @if($notes->isEmpty())
    <div class="notes-empty-state">
        <iconify-icon icon="lucide:notebook" class="notes-empty-icon"></iconify-icon>
        <p>Aún no tienes notas. ¡Crea tu primera nota ahora!</p>
    </div>
    @else
    <div class="notes-grid">
        @foreach($notes as $note)
        <div class="note-card">
            <div class="note-header">
                <strong>{{ $note->title }}</strong>
            </div>
            <hr>
            <div class="note-body">
                <p>{{ Str::limit(strip_tags($note->content), 120) }}</p>
            </div>
            <hr>
            <div class="note-actions">
                <a href="{{ route('dashboard.features.notes.edit', $note->id) }}" class="btn-edit" title="Editar">
                    <iconify-icon icon="lucide:edit"></iconify-icon>
                    Editar
                </a>
                <form action="{{ route('dashboard.features.notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta nota?')">
                    @csrf
                    <button type="submit" class="btn-delete" title="Eliminar">
                        <iconify-icon icon="lucide:trash-2"></iconify-icon>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
