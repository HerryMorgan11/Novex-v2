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
                <form action="{{ route('dashboard.features.notes.destroy', $note->id) }}" method="POST" class="note-delete-form">
                    @csrf
                    <button type="button" class="btn-delete btn-delete-trigger" title="Eliminar">
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

<!-- Modal de confirmación para eliminar nota -->
<div id="delete-note-modal" class="note-modal-overlay" style="display:none;" aria-modal="true" role="dialog" aria-labelledby="delete-modal-title">
    <div class="note-modal">
        <div class="note-modal-icon">
            <iconify-icon icon="lucide:trash-2"></iconify-icon>
        </div>
        <h2 id="delete-modal-title" class="note-modal-title">¿Deseas eliminar la nota?</h2>
        <p class="note-modal-desc">Esta acción no se puede deshacer.</p>
        <div class="note-modal-actions">
            <button id="delete-modal-cancel" class="note-modal-btn note-modal-btn--cancel">Cancelar</button>
            <button id="delete-modal-confirm" class="note-modal-btn note-modal-btn--delete">Eliminar</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    let pendingForm = null;

    document.querySelectorAll('.btn-delete-trigger').forEach(function (btn) {
        btn.addEventListener('click', function () {
            pendingForm = btn.closest('.note-delete-form');
            document.getElementById('delete-note-modal').style.display = 'flex';
        });
    });

    document.getElementById('delete-modal-cancel').addEventListener('click', function () {
        document.getElementById('delete-note-modal').style.display = 'none';
        pendingForm = null;
    });

    document.getElementById('delete-modal-confirm').addEventListener('click', function () {
        if (pendingForm) {
            pendingForm.submit();
        }
    });

    document.getElementById('delete-note-modal').addEventListener('click', function (e) {
        if (e.target === this) {
            this.style.display = 'none';
            pendingForm = null;
        }
    });
})();
</script>
@endpush
@endsection
