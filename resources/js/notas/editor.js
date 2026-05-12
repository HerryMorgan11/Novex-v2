import { Editor } from 'https://esm.sh/@tiptap/core';
import StarterKit from 'https://esm.sh/@tiptap/starter-kit';

const MAX_CHARS = 10000;

function initNoteEditor(
    editorSelector = '#editor',
    contentInputSelector = '#content-input',
    contentSelector = '#content'
) {
    const editorElement = document.querySelector(editorSelector);
    const contentInput =
        document.querySelector(contentInputSelector) || document.querySelector(contentSelector);
    const toolbarButtons = document.querySelectorAll('.toolbar-btn');
    const charCountEl = document.querySelector('#char-count');
    const charBar = document.querySelector('#char-bar');
    const charCounter = document.querySelector('#char-counter');
    const charLimitError = document.querySelector('#char-limit-error');
    const noteForm = document.querySelector('#note-form');

    if (!editorElement || !contentInput) {
        console.warn('Note editor elements not found');
        return;
    }

    function updateCharCounter(text) {
        const len = text.length;
        const pct = Math.min((len / MAX_CHARS) * 100, 100);
        charCountEl.textContent = len.toLocaleString('es-ES');
        charBar.style.width = pct + '%';
        charCounter.classList.toggle('is-warning', len >= MAX_CHARS * 0.85 && len < MAX_CHARS);
        charCounter.classList.toggle('is-danger', len >= MAX_CHARS);
        charLimitError.classList.toggle('is-visible', len >= MAX_CHARS);
    }

    const editor = new Editor({
        element: editorElement,
        extensions: [StarterKit],
        content: contentInput.value || '',
        onCreate({ editor }) {
            contentInput.value = editor.getHTML();
            updateCharCounter(editor.getText());
            updateToolbarState();
        },
        onUpdate({ editor }) {
            const text = editor.getText();
            if (text.length > MAX_CHARS) {
                editor.commands.undo();
                return;
            }
            contentInput.value = editor.getHTML();
            updateCharCounter(text);
            updateToolbarState();
        },
        onSelectionUpdate: updateToolbarState,
    });

    function updateToolbarState() {
        toolbarButtons.forEach(btn => {
            const action = btn.getAttribute('data-action');
            const level = Number.parseInt(btn.getAttribute('data-level') ?? '', 10);
            let isActive = false;

            if (action === 'bold') isActive = editor.isActive('bold');
            if (action === 'italic') isActive = editor.isActive('italic');
            if (action === 'heading') isActive = editor.isActive('heading', { level });
            if (action === 'bulletList') isActive = editor.isActive('bulletList');
            if (action === 'orderedList') isActive = editor.isActive('orderedList');

            btn.classList.toggle('is-active', isActive);
        });
    }

    toolbarButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const action = btn.getAttribute('data-action');
            const level = btn.getAttribute('data-level');

            if (action === 'bold') editor.chain().focus().toggleBold().run();
            if (action === 'italic') editor.chain().focus().toggleItalic().run();
            if (action === 'heading')
                editor
                    .chain()
                    .focus()
                    .toggleHeading({
                        level: Number.parseInt(level, 10),
                    })
                    .run();
            if (action === 'bulletList') editor.chain().focus().toggleBulletList().run();
            if (action === 'orderedList') editor.chain().focus().toggleOrderedList().run();
            if (action === 'undo') editor.chain().focus().undo().run();
            if (action === 'redo') editor.chain().focus().redo().run();

            updateToolbarState();
        });
    });

    if (noteForm) {
        noteForm.addEventListener('submit', () => {
            contentInput.value = editor.getHTML();
        });
    }
}

document.addEventListener('DOMContentLoaded', initNoteEditor);
