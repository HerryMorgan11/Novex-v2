@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/notes.css'])
@endpush

@section('content')
<div class="notes-container" style="max-width: 800px; margin: 0 auto;">
    <div class="notes-header">
        <h1>Editar Nota</h1>
        <a href="{{ route('dashboard.features.notes.index') }}" style="color: var(--muted); text-decoration: none; font-weight: 500;">
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
                    <button type="button" onclick="window.editor.chain().focus().toggleBold().run()" class="toolbar-btn" title="Negrita">
                        <iconify-icon icon="lucide:bold"></iconify-icon>
                    </button>
                    <button type="button" onclick="window.editor.chain().focus().toggleItalic().run()" class="toolbar-btn" title="Cursiva">
                        <iconify-icon icon="lucide:italic"></iconify-icon>
                    </button>
                    <button type="button" onclick="window.editor.chain().focus().toggleBulletList().run()" class="toolbar-btn" title="Lista">
                        <iconify-icon icon="lucide:list"></iconify-icon>
                    </button>
                    <button type="button" onclick="window.editor.chain().focus().toggleOrderedList().run()" class="toolbar-btn" title="Lista numerada">
                        <iconify-icon icon="lucide:list-ordered"></iconify-icon>
                    </button>
                </div>
                <div id="editor"></div>
            </div>
            <input type="hidden" name="content" id="content" value="{{ old('content', $note->content) }}">
        </div>

        <button type="submit" class="btn-save">
            Actualizar Nota
        </button>
    </form>
</div>

<!-- Tiptap Script -->
<script type="module">
    import {
        Editor
    } from 'https://esm.sh/@tiptap/core'
    import StartingKit from 'https://esm.sh/@tiptap/starter-kit'

    window.editor = new Editor({
        element: document.querySelector('#editor'),
        extensions: [
            StartingKit,
        ],
        content: `{!! old('content', $note->content) !!}`,
        onUpdate({
            editor
        }) {
            document.querySelector('#content').value = editor.getHTML()
        },
    })
</script>
@endsection