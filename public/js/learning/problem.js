document.addEventListener('DOMContentLoaded', function() {
    const cfg = window.learningConfig || {};
    const csrfToken = cfg.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Elements
    const noteModal = document.getElementById('noteModal');
    const openNoteModalBtn = document.getElementById('openNoteModal');
    const floatingNoteButton = document.getElementById('floatingNoteButton');
    const closeNoteModalBtn = document.getElementById('closeNoteModal');
    const noteForm = document.getElementById('noteForm');
    const saveNoteBtn = document.getElementById('saveNoteBtn');
    const notesContainer = document.querySelector('.space-y-4');

    // Draw modal
    const drawModal = document.getElementById('drawModal');
    const closeDrawModalBtn = document.getElementById('closeDrawModal');
    const clearCanvasBtn = document.getElementById('clearCanvas');
    const notesCanvas = document.getElementById('notesCanvas');
    const ctx = notesCanvas ? notesCanvas.getContext('2d') : null;

    // Helper: escape
    function escapeHtml(unsafe) {
        if (!unsafe && unsafe !== 0) return '';
        return String(unsafe)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Render note HTML
    function renderNoteHtml(note) {
        const createdAt = note.created_at ? new Date(note.created_at).toLocaleString() : new Date().toLocaleString();
        let imageHtml = '';
        if (note.image_url) {
            imageHtml = `<div class="mt-2"><img src="${escapeHtml(note.image_url)}" alt="apunte image" class="w-full rounded-md border"/></div>`;
        }

        return `\n                <div class="border border-gray-200 rounded-md p-4">\n                    <div class="flex justify-between items-start">\n                        <h4 class="text-md font-medium text-gray-700">${escapeHtml(note.title || 'Apunte')}</h4>\n                        <div class="flex items-center text-sm text-gray-500">\n                            <span>${escapeHtml(createdAt)}</span>\n                            <form data-note-id="${note.id}" class="ml-2 delete-note-form">\n                                <button type="button" class="text-red-500 hover:text-red-700 delete-note-btn">\n                                    <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-4 w-4\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\">\n                                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16\" />\n                                    </svg>\n                                </button>\n                            </form>\n                        </div>\n                    </div>\n                    <div class="mt-2 text-gray-600 whitespace-pre-line">${escapeHtml(note.content)}</div>\n                    ${imageHtml}\n                </div>\n            `;
    }

    // ---------- Note modal handlers ----------
    function openNoteModal() {
        if (!noteModal) return;
        noteModal.classList.remove('hidden');
        const titleEl = document.getElementById('title');
        if (titleEl) titleEl.focus();
    }
    function closeNoteModal() {
        if (!noteModal) return;
        noteModal.classList.add('hidden');
    }

    if (openNoteModalBtn) openNoteModalBtn.addEventListener('click', openNoteModal);
    if (floatingNoteButton) floatingNoteButton.addEventListener('click', openNoteModal);
    if (closeNoteModalBtn) closeNoteModalBtn.addEventListener('click', closeNoteModal);

    if (noteModal) {
        noteModal.addEventListener('click', function(e) {
            if (e.target === noteModal) closeNoteModal();
        });
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && noteModal && !noteModal.classList.contains('hidden')) closeNoteModal();
    });

    // AJAX save for note form
    if (noteForm) {
        noteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!saveNoteBtn) return;
            saveNoteBtn.disabled = true;
            const originalText = saveNoteBtn.innerText;
            saveNoteBtn.innerText = 'Guardando...';

            const url = cfg.saveNoteUrl || noteForm.action;
            const formData = new FormData(noteForm);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const note = data.note;
                    const noteHtml = renderNoteHtml(note);
                    const emptyMessage = notesContainer ? notesContainer.querySelector('.text-center') : null;
                    if (emptyMessage) emptyMessage.remove();
                    if (notesContainer) notesContainer.insertAdjacentHTML('afterbegin', noteHtml);
                    closeNoteModal();
                    noteForm.reset();
                } else {
                    alert(data.message || 'Ocurrió un error al guardar el apunte.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Ocurrió un error al guardar el apunte.');
            })
            .finally(() => {
                saveNoteBtn.disabled = false;
                saveNoteBtn.innerText = originalText;
            });
        });
    }

    // Delete via event delegation
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('delete-note-btn')) {
            const form = e.target.closest('.delete-note-form');
            const noteId = form.getAttribute('data-note-id');
            if (!confirm('¿Eliminar apunte?')) return;
            const deleteUrl = (cfg.deleteNoteBase || '') + '/' + noteId;

            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const node = form.closest('.border');
                    if (node) node.remove();
                } else {
                    alert(data.message || 'No se pudo eliminar la nota.');
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error al eliminar la nota.');
            });
        }
    });

    // ---------- Drawing canvas (non-persistent) ----------
    if (notesCanvas && ctx) {
        function resizeCanvas() {
            const ratio = window.devicePixelRatio || 1;
            notesCanvas.width = notesCanvas.clientWidth * ratio;
            notesCanvas.height = 400 * ratio;
            ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#111827';
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        let drawing = false; let lastX = 0; let lastY = 0;
        function startDraw(e) {
            drawing = true;
            const rect = notesCanvas.getBoundingClientRect();
            lastX = (e.clientX || (e.touches && e.touches[0].clientX)) - rect.left;
            lastY = (e.clientY || (e.touches && e.touches[0].clientY)) - rect.top;
        }
        function draw(e) {
            if (!drawing) return;
            const rect = notesCanvas.getBoundingClientRect();
            const x = (e.clientX || (e.touches && e.touches[0].clientX)) - rect.left;
            const y = (e.clientY || (e.touches && e.touches[0].clientY)) - rect.top;
            ctx.beginPath(); ctx.moveTo(lastX, lastY); ctx.lineTo(x, y); ctx.stroke(); lastX = x; lastY = y;
        }
        function endDraw() { drawing = false; }

        notesCanvas.addEventListener('mousedown', startDraw);
        notesCanvas.addEventListener('touchstart', startDraw);
        notesCanvas.addEventListener('mousemove', draw);
        notesCanvas.addEventListener('touchmove', draw);
        notesCanvas.addEventListener('mouseup', endDraw);
        notesCanvas.addEventListener('touchend', endDraw);

        if (clearCanvasBtn) clearCanvasBtn.addEventListener('click', function() { ctx.clearRect(0,0,notesCanvas.width, notesCanvas.height); });
        if (closeDrawModalBtn) closeDrawModalBtn.addEventListener('click', function() { if (drawModal) drawModal.classList.add('hidden'); });

        // Handle shift+click on floating button to open drawing modal
        if (floatingNoteButton) {
            // We need to modify the default click handler that was added earlier
            // Remove the previous click event listener first
            floatingNoteButton.removeEventListener('click', openNoteModal);
            
            // Add a new click handler that handles both normal click and shift+click
            floatingNoteButton.addEventListener('click', function(e) {
                if (e.shiftKey && drawModal) {
                    // Open drawing modal on shift+click
                    drawModal.classList.remove('hidden');
                } else {
                    // Normal click opens the note modal
                    openNoteModal();
                }
            });
        }
    }
});