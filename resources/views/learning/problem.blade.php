<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800 mb-2 md:mb-0">
                    {{ $course->name }}
                </h1>
                <div class="flex space-x-4">
                    <a href="{{ route('learning.course', ['course' => $course->id]) }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver a Problemas
                    </a>
                </div>
            </div>
            
            <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8 hover-shadow-effect">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">
                        {{ $problem->question_text }}
                    </h2>
                    
                    @if($problem->problem_statement)
                        <div class="bg-gray-50 p-4 rounded-md mb-6">
                            <p class="text-gray-700 whitespace-pre-line">{{ $problem->problem_statement }}</p>
                        </div>
                    @endif
                    
                    @if($problem->hasVideo())
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe 
                                src="https://www.youtube.com/embed/{{ $problem->youtube_id }}" 
                                title="YouTube video player" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen
                                class="rounded-lg shadow-lg">
                            </iframe>
                        </div>
                    @else
                        <div class="aspect-w-16 aspect-h-9 mb-6 bg-gray-100 flex items-center justify-center rounded-lg">
                            <p class="text-gray-500">El video no está disponible</p>
                        </div>
                    @endif
                    
                    @if($problem->explanation)
                        <div class="mt-6">
                            <h3 class="text-md font-medium text-gray-800 mb-2">Explicación</h3>
                            <div class="bg-blue-50 p-4 rounded-md">
                                <p class="text-gray-700">{{ $problem->explanation }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Notepad panel has been removed -->
            
            <div class="bg-white shadow-md rounded-lg overflow-hidden hover-shadow-effect">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Mis Apuntes</h3>
                        <button id="openNoteModal" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nuevo Apunte
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($notes as $note)
                            <div class="border border-gray-200 rounded-md p-4">
                                <div class="flex justify-between items-start">
                                    <h4 class="text-md font-medium text-gray-700">{{ $note->title }}</h4>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <span>{{ $note->created_at->format('d/m/Y H:i') }}</span>
                                        <form action="{{ route('learning.deleteNote', $note->id) }}" method="POST" class="ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="mt-2 text-gray-600 whitespace-pre-line">
                                            {{ $note->content }}
                                            @if(!empty($note->image_path))
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $note->image_path) }}" alt="Apunte imagen" class="w-full rounded-md border" />
                                                </div>
                                            @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-gray-500">Aún no has creado apuntes para este problema.</p>
                                <p class="text-sm text-gray-400 mt-1">Haz clic en "Nuevo Apunte" para crear tu primer apunte.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botón flotante para crear apuntes -->
    <div class="fixed bottom-6 right-6">
        <button id="floatingNoteButton" class="h-14 w-14 rounded-full bg-indigo-600 text-white flex items-center justify-center shadow-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </button>
    </div>
    
    <!-- Modal para crear apuntes -->
    <div id="noteModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-50"></div>
            </div>
            
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-auto relative z-10">
                <form id="noteForm" action="{{ route('learning.saveNote', ['course' => $course->id, 'problem' => $problem->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6">
                        <h3 class="text-xl font-medium text-gray-900 mb-4">
                            {{ strtoupper(config('app.name')) == config('app.name') ? strtoupper('Crear Apunte') : 'Crear Apunte' }}
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                                <input type="text" name="title" id="title" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Título del apunte">
                            </div>
                            
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                                <textarea name="content" id="content" rows="6" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Escribe tus apuntes aquí..." required></textarea>
                            </div>
                            
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Imagen (opcional)</label>
                                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-3 flex items-center justify-end space-x-3 rounded-b-lg">
                        <button type="button" id="closeNoteModal" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </button>
                        <button type="submit" id="saveNoteBtn" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Small config for external JS
        window.learningConfig = {
            csrfToken: '{{ csrf_token() }}',
            saveNoteUrl: '{{ route('learning.saveNote', ['course' => $course->id, 'problem' => $problem->id]) }}',
            deleteNoteBase: '{{ url('/learning/note') }}'
        };
    </script>
    <script src="{{ asset('js/learning/problem.js') }}"></script>
    </script>
    
    <!-- Agregar el aspect ratio para los videos de YouTube -->
    <style>
        .aspect-w-16 {
            position: relative;
            padding-bottom: 56.25%;
        }
        .aspect-w-16 iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
    
    <!-- Modal de dibujo (no persiste en BD) -->
    <div id="drawModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-black opacity-50"></div>
            </div>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl mx-auto relative z-10">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-medium text-gray-900">{{ strtoupper(config('app.name')) == config('app.name') ? strtoupper('Apunte Rápido (Dibujo)') : 'Apunte Rápido (Dibujo)' }}</h3>
                    </div>
                    <div class="border rounded-md">
                        <canvas id="notesCanvas" style="width:100%; height:400px; display:block;"></canvas>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Este dibujo no se guardará en la base de datos. Puedes copiarlo como imagen si lo deseas.</p>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex items-center justify-end space-x-3 rounded-b-lg">
                    <button id="clearCanvas" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Limpiar
                    </button>
                    <button id="closeDrawModal" class="px-4 py-2 bg-red-600 border border-transparent rounded-md font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Drawing functionality is now handled entirely in problem.js -->
</x-app-layout>