<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">
                    Problemas con Solución en Video - {{ $course->name }}
                </h1>
                <a href="{{ route('learning.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver a Aprendizaje
                </a>
            </div>
            
            <div class="space-y-6">
                @forelse($problems as $problem)
                <div class="bg-white shadow-md rounded-lg overflow-hidden hover-shadow-effect">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900 mb-2">
                                    {{ $problem->question_text }}
                                </h2>
                                
                                @if($problem->problem_statement)
                                    <div class="mt-3 bg-gray-50 p-4 rounded-md">
                                        <p class="text-gray-700 whitespace-pre-line">{{ $problem->problem_statement }}</p>
                                    </div>
                                @endif
                                
                                <div class="mt-4 flex flex-wrap items-center text-sm text-gray-500 gap-4">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Dificultad: {{ ucfirst($problem->difficulty_level) }}
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                        </svg>
                                        {{ $problem->notes->count() }} apuntes guardados
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ route('learning.problem', ['course' => $course->id, 'problem' => $problem->id]) }}" class="btn btn-primary inline-flex items-center px-4 py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Ver Solución en Video
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white shadow-md rounded-lg p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-gray-500 mb-4">No hay problemas con solución en video disponibles para este curso.</p>
                    <a href="{{ route('learning.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Explorar Otros Cursos
                    </a>
                </div>
                @endforelse
            </div>
            
            <!-- Paginación -->
            <div class="mt-6">
                {{ $problems->links() }}
            </div>
        </div>
    </div>
</x-app-layout>