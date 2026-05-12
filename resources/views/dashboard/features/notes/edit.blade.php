@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/notes/notes.css'])
@endpush

@section('content')
<div class="notes-container notes-container--narrow">
    <div class="notes-header">
        <h1>Editar Nota</h1>
        <a href="{{ route('dashboard.features.notes.index') }}" class="notes-back-link">
            <iconify-icon icon="lucide:arrow-left"></iconify-icon>
            Volver
        </a>
    </div>

    <form action="{{ route('dashboard.features.notes.update', $note->id) }}" method="POST" id="note-form">
        @csrf
        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Escribe un título para tu nota..." value="{{ old('title', $note->title) }}" required>
        </div>

        <div class="form-group">
            <label for="editor">Contenido</label>
            <div class="tiptap-editor">
                <div class="editor-toolbar">
                    <button type="button" class="toolbar-btn" data-action="bold" title="Negrita">
                        <iconify-icon icon="lucide:bold"></iconify-icon>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="italic" title="Cursiva">
                        <iconify-icon icon="lucide:italic"></iconify-icon>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="heading" data-level="1" title="Título 1">
                        <iconify-icon icon="lucide:heading-1"></iconify-icon>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="heading" data-level="2" title="Título 2">
                        <iconify-icon icon="lucide:heading-2"></iconify-icon>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="bulletList" title="Lista con viñetas">
                        <iconify-icon icon="lucide:list"></iconify-icon>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="orderedList" title="Lista numerada">
                        <iconify-icon icon="lucide:list-ordered"></iconify-icon>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="undo" title="Deshacer">
                        <iconify-icon icon="lucide:undo"></iconify-icon>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="redo" title="Rehacer">
                        <iconify-icon icon="lucide:redo"></iconify-icon>
                    </button>
                </div>
                <div id="editor"></div>
            </div>
            <input type="hidden" name="content" id="content" value="{{ old('content', $note->content) }}">
            <div class="char-counter" id="char-counter">
                <div class="char-counter-bar"><div class="char-counter-bar-fill" id="char-bar" style="width:0%"></div></div>
                <span id="char-count">0</span> / 10 000
            </div>
            <div class="char-limit-error" id="char-limit-error">
                <iconify-icon icon="lucide:alert-circle"></iconify-icon>
                Has alcanzado el límite de 10 000 caracteres. No puedes añadir más texto.
            </div>
        </div>

        <button type="submit" class="btn-save">
            Actualizar Nota
        </button>
    </form>
</div>

@push('scripts')
@vite(['resources/js/notas/editor.js'])
@endpush
@endsection
