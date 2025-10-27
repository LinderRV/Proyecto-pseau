document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('aiChatButton');
    const win = document.getElementById('aiChatWindow');
    const close = document.getElementById('aiChatClose');
    const clearBtn = document.getElementById('aiChatClear');
    const form = document.getElementById('aiChatForm');
    const input = document.getElementById('aiChatInput');
    const imageInput = document.getElementById('aiChatImage');
    const imagePreviewContainer = document.getElementById('aiChatImagePreview');
    const previewImg = document.getElementById('aiChatPreviewImg');
    const removeImageBtn = document.getElementById('aiChatRemoveImage');
    const messages = document.getElementById('aiChatMessages');

    if (!btn || !win) return;

    // Restore chat open/closed state from previous page (so it stays open across navigation)
    try {
        const wasOpen = localStorage.getItem('aiChatOpen');
        if (wasOpen === '1') {
            // ensure window is visible
            win.classList.remove('hidden');
            // load history
            fetch('/ai/gemini/history')
                .then(res => res.json())
                .then(data => {
                    messages.innerHTML = '';
                    if (data && data.messages && Array.isArray(data.messages)) {
                        data.messages.forEach(m => {
                            appendMessage(m.role === 'ai' ? 'ai' : 'user', m.text || '', m.imageUrl || null);
                        });
                    }
                    input.focus();
                })
                .catch(err => {
                    console.warn('No se pudo cargar el historial AI:', err);
                });
        }
    } catch (e) {
        // ignore storage errors (e.g., privacy mode)
    }

    function appendMessage(role, text, imageUrl) {
        const wrapper = document.createElement('div');
        wrapper.className = 'mb-3 flex items-start ' + (role === 'user' ? 'justify-end' : 'justify-start');

        const bubble = document.createElement('div');
        bubble.className = 'max-w-[70%]';

        const meta = document.createElement('div');
        meta.className = 'text-xs text-gray-500 mb-1 ' + (role === 'user' ? 'text-right' : 'text-left');
        meta.textContent = role === 'user' ? 'Tú' : 'Peki';

        const content = document.createElement('div');
        content.className = (role === 'user' ? 'bg-gray-100 text-gray-800' : 'bg-indigo-50 text-gray-900') + ' p-3 rounded-lg shadow-sm';

        if (text) {
            const p = document.createElement('div');
            p.className = 'whitespace-pre-wrap';
            p.innerHTML = escapeHtml(text);
            content.appendChild(p);
        }

        if (imageUrl) {
            const imgWrap = document.createElement('div');
            imgWrap.className = 'mt-2 flex space-x-2 overflow-x-auto';
            const img = document.createElement('img');
            img.src = imageUrl;
            img.className = 'h-20 w-20 object-cover rounded';
            imgWrap.appendChild(img);
            content.appendChild(imgWrap);
        }

        bubble.appendChild(meta);
        bubble.appendChild(content);

        wrapper.appendChild(bubble);
        messages.appendChild(wrapper);
        messages.scrollTop = messages.scrollHeight;
    }

    function escapeHtml(unsafe) {
        return String(unsafe)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    btn.addEventListener('click', function () {
        // Toggle window and load cached history when opening
        const wasHidden = win.classList.contains('hidden');
        win.classList.toggle('hidden');
        // Persist open/closed state
        try { localStorage.setItem('aiChatOpen', wasHidden ? '1' : '0'); } catch(e) {}
        if (wasHidden) {
            // load history
            fetch('/ai/gemini/history')
                .then(res => res.json())
                .then(data => {
                    messages.innerHTML = '';
                    if (data && data.messages && Array.isArray(data.messages)) {
                        data.messages.forEach(m => {
                            appendMessage(m.role === 'ai' ? 'ai' : 'user', m.text || '', m.imageUrl || null);
                        });
                    }
                    input.focus();
                })
                .catch(err => {
                    console.warn('No se pudo cargar el historial AI:', err);
                    input.focus();
                });
        } else {
            input.focus();
        }
    });

    close.addEventListener('click', function () {
        win.classList.add('hidden');
        try { localStorage.setItem('aiChatOpen', '0'); } catch(e) {}
    });

    clearBtn.addEventListener('click', function (e) {
        e.preventDefault();
        messages.innerHTML = '';
        // Also reset image input / preview so attaching images still works after clearing
        try {
            if (imageInput) {
                imageInput.value = '';
                imageInput.disabled = false;
            }
            if (previewImg) {
                previewImg.src = '';
            }
            if (imagePreviewContainer) {
                imagePreviewContainer.classList.add('hidden');
            }
        } catch (err) {
            console.warn('Error resetting image preview after clear:', err);
        }

        // Also clear server-side cached history so reload doesn't bring it back
        try {
            fetch('/ai/gemini/clear', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            }).then(r => r.json()).then(res => {
                if (!res || res.ok !== true) {
                    console.warn('No se pudo limpiar historial en servidor', res);
                }
            }).catch(err => {
                console.warn('Error al limpiar historial en servidor:', err);
            });
        } catch (err) {
            console.warn('Error invoking clear endpoint:', err);
        }
    });

    // Handle image input preview
    if (imageInput) {
        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            previewImg.src = url;
            imagePreviewContainer.classList.remove('hidden');
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function (e) {
            e.preventDefault();
            if (imageInput) imageInput.value = '';
            previewImg.src = '';
            imagePreviewContainer.classList.add('hidden');
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const text = input.value.trim();
        // allow sending either text or image
        if (!text && (!imageInput || !imageInput.files || imageInput.files.length === 0)) return;

        // show user message (include image preview if present)
        const hasImage = (imageInput && imageInput.files && imageInput.files.length > 0);
        const imagePreviewUrl = hasImage ? previewImg.src : null;
        if (text || hasImage) appendMessage('user', text || '', imagePreviewUrl);

        input.value = '';
        if (imageInput) imageInput.disabled = true;

        // show loader
        const loader = document.createElement('div');
        loader.className = 'mb-2 text-sm text-gray-500';
        loader.textContent = 'Escribiendo...';
        messages.appendChild(loader);
        messages.scrollTop = messages.scrollHeight;

        // If there's an image, send as FormData
        if (imageInput && imageInput.files && imageInput.files.length > 0) {
            const fd = new FormData();
            fd.append('message', text || '');
            fd.append('image', imageInput.files[0]);
            fetch('/ai/gemini/chat', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: fd
            }).then(res => res.json()).then(data => {
                loader.remove();
                if (data.error) {
                    appendMessage('ai', 'Error: ' + (data.error || 'Problema al generar respuesta'));
                } else {
                    appendMessage('ai', data.reply || JSON.stringify(data), data.imageUrl || null);
                }
                // reset image input and preview
                if (imageInput) { imageInput.value = ''; imageInput.disabled = false; }
                previewImg.src = ''; imagePreviewContainer.classList.add('hidden');
            }).catch(err => {
                loader.remove();
                appendMessage('ai', 'Error en la conexión.');
                if (imageInput) { imageInput.value = ''; imageInput.disabled = false; }
                previewImg.src = ''; imagePreviewContainer.classList.add('hidden');
            });
        } else {
            fetch('/ai/gemini/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: text })
            })
                .then(res => res.json())
                .then(data => {
                    loader.remove();
                    if (data.error) {
                        appendMessage('ai', 'Error: ' + (data.error || 'Problema al generar respuesta'));
                    } else {
                        appendMessage('ai', data.reply || JSON.stringify(data), data.imageUrl || null);
                    }
                })
                .catch(err => {
                    loader.remove();
                    appendMessage('ai', 'Error en la conexión.');
                });
        }
    });
});
