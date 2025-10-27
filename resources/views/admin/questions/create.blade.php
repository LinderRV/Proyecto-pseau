<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Crear Nueva Pregunta</h2>
                            <p class="text-gray-600">Curso: {{ $course->name }}</p>
                        </div>
                        <a href="{{ route('admin.courses.questions', $course) }}" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Volver a Preguntas
                        </a>
                    </div>
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.questions.store', $course) }}" method="POST" id="questionForm" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="question_text" class="block text-sm font-medium text-gray-700">Texto de la Pregunta</label>
                                <textarea name="question_text" id="question_text" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>{{ old('question_text') }}</textarea>
                            </div>
                            
                            <div>
                                <label for="problem_statement" class="block text-sm font-medium text-gray-700">Enunciado del Problema (opcional)</label>
                                <textarea name="problem_statement" id="problem_statement" rows="4" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('problem_statement') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">Utiliza este campo para proporcionar contexto adicional sobre el problema.</p>
                            </div>
                            
                            <div>
                                <label for="explanation" class="block text-sm font-medium text-gray-700">Explicación (opcional)</label>
                                <textarea name="explanation" id="explanation" rows="4" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('explanation') }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">La explicación será mostrada después de que el estudiante responda.</p>
                            </div>

                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700">Imagen (opcional)</label>
                                <input type="file" name="image" id="image" accept="image/*" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <p class="mt-1 text-xs text-gray-500">Sube una imagen para complementar la pregunta (formatos permitidos: JPG, PNG, GIF)</p>
                            </div>
                            
                            <div>
                                <label for="youtube_id" class="block text-sm font-medium text-gray-700">ID de YouTube (opcional)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="text" name="youtube_id" id="youtube_id" value="{{ old('youtube_id') }}" class="block w-full pr-10 sm:text-sm border border-gray-200 rounded-md px-3 py-2" placeholder="Ej: dQw4w9WgXcQ o https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M10 15l5.19-3L10 9v6z"></path></svg>
                                    </div>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Puedes pegar el ID (dQw4...) o la URL completa.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Opciones</label>
                                <p class="text-xs text-gray-500 mb-2">Agrega al menos 2 opciones. Selecciona la opción correcta (solo una).</p>
                                
                                <div id="options-container">
                                    <div class="option-item mb-2 p-3 border border-gray-300 rounded-md">
                                        <div class="flex items-center">
                                            <input type="radio" name="correct_option" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" value="0" {{ old('correct_option') == '0' ? 'checked' : '' }}>
                                            <div class="ml-2 flex-grow">
                                                <input type="text" name="options[0][text]" value="{{ old('options.0.text') }}" class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-200 rounded-md px-3 py-2" placeholder="Opción 1" required>
                                            </div>
                                                @error('correct_option')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                                @error('options')
                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                @enderror
                                        </div>
                                    </div>
                                    <div class="option-item mb-2 p-3 border border-gray-300 rounded-md">
                                        <div class="flex items-center">
                                            <input type="radio" name="correct_option" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" value="1" {{ old('correct_option') == '1' ? 'checked' : '' }}>
                                            <div class="ml-2 flex-grow">
                                                <input type="text" name="options[1][text]" value="{{ old('options.1.text') }}" class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-200 rounded-md px-3 py-2" placeholder="Opción 2" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-2">
                                    <button type="button" id="add-option" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        + Agregar Opción
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Guardar Pregunta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let optionCounter = 2;

            document.getElementById('add-option').addEventListener('click', function() {
                const container = document.getElementById('options-container');
                const newOption = document.createElement('div');
                newOption.className = 'option-item mb-2 p-3 border border-gray-300 rounded-md';
                newOption.innerHTML = `
                    <div class="flex items-center">
                        <input type="radio" name="correct_option" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" value="${optionCounter}">
                        <div class="ml-2 flex-grow">
                            <input type="text" name="options[${optionCounter}][text]" class="mt-1 block w-full shadow-sm sm:text-sm border border-gray-200 rounded-md px-3 py-2" placeholder="Opción ${optionCounter + 1}" required>
                        </div>
                        <button type="button" class="ml-2 remove-option inline-flex items-center justify-center w-8 h-8 rounded-md bg-red-50 text-red-600 hover:bg-red-100" aria-label="Eliminar opción">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                `;
                container.appendChild(newOption);
                optionCounter++;

                // Add event listener to the remove button
                const removeBtn = newOption.querySelector('.remove-option');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        container.removeChild(newOption);
                    });
                }
            });
        });
    </script>
</x-app-layout>