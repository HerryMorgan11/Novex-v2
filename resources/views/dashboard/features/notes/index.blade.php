@extends('dashboard.app.dashboard')

@push('styles')
    @vite(['resources/css/dashboard/features/notes.css'])
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
        <div style="text-align: center; padding: 50px; color: var(--muted);">
            <iconify-icon icon="lucide:notebook" style="font-size: 4rem; opacity: 0.2; margin-bottom: 20px;"></iconify-icon>
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
                            @method('DELETE')
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