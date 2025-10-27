<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-5xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Simulación de Examen de Admisión</h1>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Configura tu examen</h2>
                
                <form method="POST" action="{{ route('exams.start') }}">
                    @csrf
                    
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Universidad -->
                        <div>
                            <label for="university_id" class="block text-sm font-medium text-gray-700 mb-2">Universidad (opcional)</label>
                            <select id="university_id" name="university_id" class="form-input">
                                <option value="">Todas las universidades</option>
                                @foreach($universities as $university)
                                    <option value="{{ $university->id }}">{{ $university->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Dificultad -->
                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">Nivel de dificultad</label>
                            <select id="difficulty" name="difficulty" class="form-input" required>
                                <option value="Todos">Todos los niveles</option>
                                @foreach($difficultyLevels as $level)
                                    <option value="{{ $level }}">{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Cantidad de preguntas -->
                        <div>
                            <label for="question_count" class="block text-sm font-medium text-gray-700 mb-2">Número de preguntas</label>
                            <select id="question_count" name="question_count" class="form-input" required>
                                @foreach($questionCounts as $count)
                                    <option value="{{ $count }}" {{ $count == 20 ? 'selected' : '' }}>{{ $count }} preguntas</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Tiempo estimado (informativo) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tiempo estimado</label>
                            <div class="p-3 bg-gray-50 rounded-md text-gray-600">
                                <span id="estimated-time">40</span> minutos aproximadamente
                            </div>
                        </div>
                    </div>
                    
                    <!-- Selección de cursos -->
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Selecciona las materias para tu examen</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <!-- Mostrar los IDs para debug (eliminar después) -->
                            <div class="col-span-full mb-2 text-xs text-gray-500">
                                Cursos disponibles: {{ count($courses) }}
                            </div>
                            @foreach($courses as $course)
                                <div class="flex items-center">
                                    <input type="checkbox" id="course_{{ $course->id }}" name="course_ids[]" value="{{ $course->id }}" class="h-4 w-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                    <label for="course_{{ $course->id }}" class="ml-2 text-sm text-gray-700">{{ $course->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('course_ids')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-8 flex justify-center">
                        <button type="submit" class="btn btn-primary px-8 py-3">
                            Iniciar Simulación de Examen
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Consejos -->
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">Consejos para tu examen</h3>
                <ul class="list-disc pl-5 text-blue-700 space-y-2">
                    <li>Administra bien tu tiempo. Dedica aproximadamente 2 minutos por pregunta.</li>
                    <li>Si no sabes una respuesta, márcala y regresa después para no perder tiempo.</li>
                    <li>Lee cuidadosamente cada pregunta antes de responder.</li>
                    <li>Elimina las opciones que claramente son incorrectas para aumentar tus probabilidades.</li>
                    <li>Revisa tus respuestas al finalizar si te sobra tiempo.</li>
                </ul>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questionCountSelect = document.getElementById('question_count');
            const estimatedTimeSpan = document.getElementById('estimated-time');
            
            // Update estimated time when question count changes
            questionCountSelect.addEventListener('change', function() {
                const questionCount = parseInt(this.value);
                const estimatedMinutes = questionCount * 2; // 2 minutes per question
                estimatedTimeSpan.textContent = estimatedMinutes;
            });
            
            // Validate form before submission
            document.querySelector('form').addEventListener('submit', function(e) {
                const checkedCourses = document.querySelectorAll('input[name="course_ids[]"]:checked');
                if (checkedCourses.length === 0) {
                    e.preventDefault();
                    alert('Por favor, selecciona al menos una materia para tu examen.');
                }
            });
        });
    </script>
</x-app-layout>