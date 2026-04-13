/**
 * Subtasks - Edición inline de subtareas en la vista de detalle de recordatorio.
 * Muestra/oculta el formulario de edición al hacer clic en el texto de la subtarea.
 */

document.querySelectorAll('[data-subtask-id]').forEach(row => {
    const titleSpan = row.querySelector('.subtask-title');
    const editForm = row.querySelector('.subtask-edit-form');
    const cancelBtn = row.querySelector('.subtask-cancel');
    const input = editForm?.querySelector('input[name="title"]');

    // Abrir formulario de edición al hacer clic en el título
    titleSpan?.addEventListener('click', () => {
        titleSpan.style.display = 'none';
        editForm.style.display = 'flex';
        input?.focus();
    });

    // Cancelar edición
    cancelBtn?.addEventListener('click', () => {
        editForm.style.display = 'none';
        titleSpan.style.display = 'block';
    });

    // Cancelar edición con Escape
    input?.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            editForm.style.display = 'none';
            titleSpan.style.display = 'block';
        }
    });
});
