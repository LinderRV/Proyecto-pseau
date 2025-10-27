<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Detalles de la Práctica</h1>
                <a href="{{ route('practice.history') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al historial
                </a>
            </div>
            
            <!-- Resumen de resultados -->
            <div class="bg-white rounded-lg shadow-md mb-8">
                <div class="p-6 border-b">
                    <div class="flex flex-col md:flex-row justify-between">
                        <div>
                            <h2 class="text-xl font-medium text-gray-800">{{ $result->course->name }}</h2>
                            <p class="text-gray-500">Completado el {{ $result->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <div class="inline-flex items-center px-4 py-2 rounded-full 
                                @if($result->score >= 80) bg-green-100 text-green-800 
                                @elseif($result->score >= 60) bg-yellow-100 text-yellow-800 
                                @else bg-red-100 text-red-800 
                                @endif">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-bold">{{ number_format($result->score, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 p-6">
                    <div class="text-center p-4">
                        <p class="text-gray-500 text-sm uppercase font-medium">Total Preguntas</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $result->total_questions }}</p>
                    </div>
                    <div class="text-center p-4">
                        <p class="text-gray-500 text-sm uppercase font-medium">Respuestas Correctas</p>
                        <p class="text-2xl font-bold text-green-600">{{ $result->correct_answers }}</p>
                    </div>
                    <div class="text-center p-4">
                        <p class="text-gray-500 text-sm uppercase font-medium">Tiempo</p>
                        <p class="text-2xl font-bold text-gray-800">{{ formatTime($result->time_taken) }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Detalle de preguntas -->
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Preguntas y Respuestas</h2>
            
            <div class="space-y-6">
                @if(isset($result->question_details) && is_array($result->question_details) && count($result->question_details) > 0)
                    @foreach($result->question_details as $index => $question)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6 border-b">
                            <div class="flex justify-between">
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    @if(isset($question['is_correct']) && $question['is_correct']) 
                                        bg-green-100 text-green-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif">
                                    Pregunta {{ $index + 1 }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    @if(isset($question['is_correct']))
                                        @if($question['is_correct'])
                                            Correcta
                                        @else
                                            Incorrecta
                                        @endif
                                    @else
                                        Sin responder
                                    @endif
                                </span>
                            </div>
                        
                            <div class="mt-4">
                                <h3 class="text-lg font-medium text-gray-800">{{ $question['question_text'] ?? 'Sin texto de pregunta' }}</h3>
                                
                                @if(isset($question['options']) && is_array($question['options']))
                                    <div class="mt-4 space-y-3">
                                        @foreach($question['options'] as $option)
                                            <div class="flex items-center p-3 rounded-lg
                                                @if(isset($question['selected_option']) && isset($question['selected_option']['id']) && isset($option['id']) && $question['selected_option']['id'] == $option['id'])
                                                    @if(isset($option['is_correct']) && $option['is_correct'])
                                                        bg-green-50 border border-green-200
                                                    @else
                                                        bg-red-50 border border-red-200
                                                    @endif
                                                @elseif(isset($option['is_correct']) && $option['is_correct'])
                                                    bg-blue-50 border border-blue-200
                                                @else
                                                    bg-gray-50
                                                @endif
                                                ">
                                                <div class="flex-shrink-0">
                                                    @if(isset($question['selected_option']) && isset($question['selected_option']['id']) && isset($option['id']) && $question['selected_option']['id'] == $option['id'])
                                                        @if(isset($option['is_correct']) && $option['is_correct'])
                                                            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                            </svg>
                                                        @endif
                                                    @elseif(isset($option['is_correct']) && $option['is_correct'])
                                                        <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium 
                                                        @if(isset($question['selected_option']) && isset($question['selected_option']['id']) && isset($option['id']) && $question['selected_option']['id'] == $option['id'])
                                                            @if(isset($option['is_correct']) && $option['is_correct'])
                                                                text-green-800
                                                            @else
                                                                text-red-800
                                                            @endif
                                                        @elseif(isset($option['is_correct']) && $option['is_correct'])
                                                            text-blue-800
                                                        @else
                                                            text-gray-800
                                                        @endif
                                                    ">
                                                        {{ $option['option_text'] ?? 'Sin texto de opción' }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                        <p class="text-sm text-gray-500">No hay opciones disponibles para esta pregunta.</p>
                                    </div>
                                @endif
                            
                            @if(isset($question['explanation']) && $question['explanation'])
                                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                    <p class="text-sm text-blue-800">
                                        <strong>Explicación:</strong> {{ $question['explanation'] }}
                                    </p>
                                </div>
                            @endif
                            
                            <!-- Información adicional de la pregunta -->
                            <div class="mt-4 flex flex-wrap text-xs text-gray-500">
                                <div class="mr-4">
                                    <span class="font-medium">Dificultad:</span> 
                                    {{ isset($question['difficulty_level']) ? ucfirst($question['difficulty_level']) : 'No especificada' }}
                                </div>
                                <div>
                                    <span class="font-medium">Tema:</span> 
                                    {{ isset($question['topic']) ? $question['topic'] : 'General' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <p class="text-gray-500">No hay información detallada disponible para esta práctica.</p>
                    </div>
                @endif
            </div>
            
            <div class="mt-8 flex justify-between">
                <a href="{{ route('practice.history') }}" class="btn btn-outline">
                    Volver al historial
                </a>
                <a href="{{ route('practice.course', ['course' => $result->course_id]) }}" class="btn btn-primary">
                    Practicar de nuevo
                </a>
            </div>
        </div>
    </div>
</x-app-layout>