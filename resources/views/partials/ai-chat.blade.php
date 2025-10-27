<!-- Floating AI chat button + window -->
<div id="aiChatRoot">
    <button id="aiChatButton" class="fixed z-60 bottom-6 right-6 w-14 h-14 rounded-full bg-blue-600 hover:bg-blue-700 text-white shadow-lg flex items-center justify-center" title="Asistente AI">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
        </svg>
    </button>

    <div id="aiChatWindow" class="fixed hidden z-70 bottom-20 right-6 w-80 h-96 bg-white rounded-xl shadow-2xl flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-4 py-2 bg-blue-600 text-white rounded-t-xl">
            <div class="flex items-center space-x-3">
                <div class="h-8 w-8 bg-white bg-opacity-10 rounded-full flex items-center justify-center">🤖</div>
                <div>
                    <div class="text-sm font-semibold">Asistente Virtual</div>
                    <div class="text-xs opacity-80">Peki</div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button id="aiChatClear" class="text-sm opacity-90 hover:opacity-100">Limpiar</button>
                <button id="aiChatClose" class="text-xl font-bold">−</button>
            </div>
        </div>

        <div id="aiChatMessages" class="flex-1 p-3 overflow-auto text-sm bg-white">
            <!-- messages will be injected here -->
        </div>

        <div class="p-2 border-t bg-white">
            <form id="aiChatForm" class="flex items-center space-x-2" enctype="multipart/form-data">
                <label for="aiChatImage" class="inline-flex items-center justify-center p-2 rounded hover:bg-gray-100 cursor-pointer" title="Adjuntar imagen">
                    {{-- Use custom paperclip image at public/img/paperclip.png. If you prefer another filename, update the path here. --}}
                    <img src="{{ asset('img/paperclip.svg') }}" alt="Adjuntar" class="h-5 w-5 object-contain" onerror="this.style.display='none'" />
                    {{-- Fallback icon if image not found --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l6 6 4-4 8 8v2H3V7z" />
                    </svg>
                </label>
                <input id="aiChatImage" name="image" type="file" accept="image/*" class="hidden" />
                <input id="aiChatInput" name="message" type="text" placeholder="Escribe un mensaje..." class="flex-1 px-3 py-2 border rounded-full focus:outline-none" />
                <button type="submit" class="p-2 rounded-full bg-blue-600 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 2L11 13" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M22 2L15 22l-4-9-9-4 19-7z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </form>
            <div id="aiChatImagePreview" class="mt-2 px-2 hidden">
                <div class="flex items-center space-x-2">
                    <img id="aiChatPreviewImg" src="" alt="Preview" class="h-12 w-12 object-cover rounded" />
                    <button id="aiChatRemoveImage" class="text-sm text-gray-600">Eliminar</button>
                </div>
            </div>
        </div>

    <script src="/js/aiChat.js"></script>
</div>
