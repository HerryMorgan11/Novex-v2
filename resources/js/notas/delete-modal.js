function initDeleteNoteModal() {
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
}

document.addEventListener('DOMContentLoaded', initDeleteNoteModal);
