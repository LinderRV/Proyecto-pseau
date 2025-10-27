<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $course->name }}</h1>
                    <p class="text-gray-600">{{ $course->description }}</p>
                </div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold mt-2 md:mt-0">
                    {{ $questionCount }} preguntas disponibles
                </span>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Comenzar práctica</h2>
                
                <form method="POST" action="{{ route('practice.start', $course->id) }}">
                    @csrf
                    
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Nivel de dificultad -->
                        <div>
                            <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">Nivel de dificultad</label>
                            <select id="difficulty" name="difficulty" class="form-input" required>
                                <option value="Todos">Todos los niveles</option>
                                @foreach($difficultyLevels as $level)
                                    <option value="{{ $level }}">{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Número de preguntas -->
                        <div>
                            <label for="question_count" class="block text-sm font-medium text-gray-700 mb-2">Número de preguntas</label>
                            <select id="question_count" name="question_count" class="form-input" required>
                                @if(empty($availableQuestionCounts))
                                    <option value="5">5 preguntas</option>
                                @else
                                    @foreach($availableQuestionCounts as $count)
                                        <option value="{{ $count }}" {{ $count == 10 ? 'selected' : '' }}>{{ $count }} preguntas</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-center">
                        <button type="submit" class="btn btn-primary px-8 py-3">
                            Comenzar Práctica
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tips para esta materia -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Consejos para esta materia</h3>
                    <ul class="list-disc pl-5 text-gray-600 space-y-2">
                        <li>Practica regularmente para reforzar conceptos clave</li>
                        <li>Toma notas de los errores frecuentes</li>
                        <li>Estudia las explicaciones para entender los conceptos</li>
                        <li>Incrementa gradualmente la dificultad de las preguntas</li>
                    </ul>
                </div>
                
                <!-- Estadísticas de la materia -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Importancia por carrera</h3>
                    <div class="space-y-3">
                        @foreach($course->careers as $career)
                            <div>
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>{{ $career->name }}</span>
                                    <span>{{ $career->pivot->importance }}/10</span>
                                </div>
                                <div class="relative w-full h-2 bg-gray-200 rounded-full">
                                    <div class="absolute top-0 left-0 h-2 bg-blue-500 rounded-full" 
                                         style="width: {{ ($career->pivot->importance / 10) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>