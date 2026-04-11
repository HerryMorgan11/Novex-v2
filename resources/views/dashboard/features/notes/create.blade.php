@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/notes.css'])
<style>
    .tiptap-editor {
        margin-top: 10px;
    }
</style>
@endpush

@section('content')
<div class="notes-container" style="max-width: 800px; margin: 0 auto;">
    <div class="notes-header">
        <h1>Nueva Nota</h1>
        <a href="{{ route('dashboard.features.notes.index') }}" style="color: var(--muted); text-decoration: none; font-weight: 500;">
            <iconify-icon icon="lucide:arrow-left"></iconify-icon>
            Volver
        </a>
    </div>

    <form action="{{ route('dashboard.features.notes.store') }}" method="POST" id="note-form">
        @csrf
        <div class="form-group">
            <label for="title">Título</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Escribe un título para tu nota" required autofocus>
        </div>

        <div class="form-group">
            <label>Contenido</label>
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
                    <button type="button" class="toolbar-btn" data-action="bulletList" title="Lista">
                        <iconify-icon icon="lucide:list"></iconify-icon>
                    </button>
                    <button type="button" class="toolbar-btn" data-action="orderedList" title="Lista ordenada">
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
            <input type="hidden" name="content" id="content-input">
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-save">
                Crear Nota
            </button>
        </div>
    </form>
</div>

<script type="module">
    import {
        Editor
    } from 'https://esm.sh/@tiptap/core'
    import StarterKit from 'https://esm.sh/@tiptap/starter-kit'

    const editor = new Editor({
        element: document.querySelector('#editor'),
        extensions: [
            StarterKit,
        ],
        content: '',
        onUpdate({
            editor
        }) {
            document.querySelector('#content-input').value = editor.getHTML()
        },
    })

    // Toolbar actions
    document.querySelectorAll('.toolbar-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const action = btn.getAttribute('data-action')
            const level = btn.getAttribute('data-level')

            if (action === 'bold') editor.chain().focus().toggleBold().run()
            if (action === 'italic') editor.chain().focus().toggleItalic().run()
            if (action === 'heading') editor.chain().focus().toggleHeading({
                level: parseInt(level)
            }).run()
            if (action === 'bulletList') editor.chain().focus().toggleBulletList().run()
            if (action === 'orderedList') editor.chain().focus().toggleOrderedList().run()
            if (action === 'undo') editor.chain().focus().undo().run()
            if (action === 'redo') editor.chain().focus().redo().run()
        })
    })

    // Sync content on form submit
    document.querySelector('#note-form').addEventListener('submit', () => {
        document.querySelector('#content-input').value = editor.getHTML()
    })
</script>
@endsection