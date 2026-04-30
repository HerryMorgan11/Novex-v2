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
        </div>

        <button type="submit" class="btn-save">
            Actualizar Nota
        </button>
    </form>
</div>

@push('scripts')
<script type="module">
    import { Editor } from 'https://esm.sh/@tiptap/core'
    import StarterKit from 'https://esm.sh/@tiptap/starter-kit'

    const contentInput = document.querySelector('#content')
    const toolbarButtons = document.querySelectorAll('.toolbar-btn')

    const editor = new Editor({
        element: document.querySelector('#editor'),
        extensions: [StarterKit],
        content: @js(old('content', $note->content)),
        onCreate({ editor }) {
            contentInput.value = editor.getHTML()
            updateToolbarState()
        },
        onUpdate({ editor }) {
            contentInput.value = editor.getHTML()
            updateToolbarState()
        },
        onSelectionUpdate: updateToolbarState,
    })

    function updateToolbarState() {
        toolbarButtons.forEach((btn) => {
            const action = btn.getAttribute('data-action')
            const level = Number.parseInt(btn.getAttribute('data-level') ?? '', 10)
            let isActive = false

            if (action === 'bold') isActive = editor.isActive('bold')
            if (action === 'italic') isActive = editor.isActive('italic')
            if (action === 'heading') isActive = editor.isActive('heading', { level })
            if (action === 'bulletList') isActive = editor.isActive('bulletList')
            if (action === 'orderedList') isActive = editor.isActive('orderedList')

            btn.classList.toggle('is-active', isActive)
        })
    }

    toolbarButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const action = btn.getAttribute('data-action')
            const level = btn.getAttribute('data-level')

            if (action === 'bold') editor.chain().focus().toggleBold().run()
            if (action === 'italic') editor.chain().focus().toggleItalic().run()
            if (action === 'heading') editor.chain().focus().toggleHeading({ level: Number.parseInt(level, 10) }).run()
            if (action === 'bulletList') editor.chain().focus().toggleBulletList().run()
            if (action === 'orderedList') editor.chain().focus().toggleOrderedList().run()
            if (action === 'undo') editor.chain().focus().undo().run()
            if (action === 'redo') editor.chain().focus().redo().run()

            updateToolbarState()
        })
    })

    document.querySelector('#note-form').addEventListener('submit', () => {
        contentInput.value = editor.getHTML()
    })
</script>
@endpush
@endsection
