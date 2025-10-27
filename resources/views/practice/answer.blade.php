<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Revisando tu respuesta</h1>
                <div class="text-sm font-medium {{ $isCorrect ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} py-1 px-3 rounded-full">
                    {{ $isCorrect ? '¡Correcto!' : 'Incorrecto' }}
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <!-- Question text -->
                <div class="mb-6">
                    <p class="text-gray-800 text-lg">{{ $question->question_text }}</p>
                    
                    @if($question->image)
                        <div class="mt-4">
                            <div class="flex justify-center">
                                <img src="{{ asset('storage/' . $question->image) }}" alt="Imagen de la pregunta" class="w-28 h-auto rounded-md shadow-sm mx-auto">
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Selected answer -->
                <div class="mb-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Tu respuesta:</h3>
                    <div class="p-3 rounded-lg {{ $isCorrect ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div class="flex items-start">
                            @if($isCorrect)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            <span class="{{ $isCorrect ? 'text-green-800' : 'text-red-800' }}">{{ $selectedOption->option_text }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Correct answer (if wrong) -->
                @if(!$isCorrect)
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Respuesta correcta:</h3>
                        <div class="p-3 rounded-lg bg-green-50 border border-green-200">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-green-800">{{ $correctOption->option_text }}</span>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Explanation -->
                @if($question->explanation)
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Explicación:</h3>
                        <p class="text-gray-700">{{ $question->explanation }}</p>
                    </div>
                @endif
            </div>
            
            <div class="flex justify-center space-x-4">
                @if($hasNext)
                    <a href="{{ route('practice.question') }}" class="btn btn-primary px-8 py-3">
                        Siguiente Pregunta
                    </a>
                @else
                    <a href="{{ route('practice.results') }}" class="btn btn-primary px-8 py-3">
                        Ver Resultados
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>