<x-app-layout>
    <div class="py-8 px-4 md:px-8 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <p class="text-sm font-medium text-indigo-600 uppercase tracking-wider mb-2">Fortalece tus conocimientos</p>
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2">Aprendizaje con Videos</h1>
            <p class="text-gray-600 mb-8 max-w-3xl">Accede a contenido educativo de alta calidad con explicaciones detalladas para comprender mejor cada tema y superar tus exámenes con éxito.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($courses as $course)
                @php
                    // Normalize icon key: remove spaces and lowercase so DB values like "Matemáticas" or " math " still match
                    $iconKey = strtolower(str_replace(' ', '', $course->icon ?? ''));
                    
                    // Map course names to icon keys
                    if($course->name == 'Matemáticas') $iconKey = 'math';
                    if($course->name == 'Física') $iconKey = 'physics';
                    if($course->name == 'Química') $iconKey = 'chemistry';
                    if($course->name == 'Biología') $iconKey = 'biology';
                    if($course->name == 'Historia') $iconKey = 'history';
                    if($course->name == 'Literatura') $iconKey = 'literature';
                @endphp
                <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 {{ getCourseColor($iconKey, 'border') }} hover:shadow-lg hover:border-gray-300 transition duration-300 ease-in-out transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center mb-3">
                            <div class="w-12 h-12 flex-shrink-0 rounded-md flex items-center justify-center {{ getCourseColor($iconKey, 'bg') }} {{ getCourseColor($iconKey, 'text') }}">
                                @if(str_starts_with($iconKey, 'http') || str_starts_with($iconKey, '/'))
                                    <img src="{{ $course->icon }}" alt="{{ $course->name }}" class="h-6 w-6 rounded object-cover">
                                @elseif($iconKey == 'math')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                @elseif($iconKey == 'chemistry')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    </svg>
                                @elseif($iconKey == 'physics')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                @elseif($iconKey == 'biology')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                @elseif($iconKey == 'history')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @elseif($iconKey == 'language' || $iconKey == 'literature')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                @elseif($iconKey == 'programming')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                @elseif($iconKey == 'economics')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h2 class="text-lg font-medium text-gray-900">{{ $course->name }}</h2>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-4">Aprende con ejemplos, soluciones explicadas en video y toma apuntes mientras estudias.</p>
                        
                        <div class="pt-4 mt-2">
                            <a href="{{ route('learning.course', $course->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-100 border border-transparent rounded-md font-medium text-xs sm:text-sm text-indigo-600 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150 w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Ver soluciones en video
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center p-8 bg-white rounded-lg shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-gray-500 text-lg">No hay cursos disponibles actualmente.</p>
                    <p class="text-gray-400 mt-2">Vuelve pronto para ver nuevo contenido.</p>
                </div>
                @endforelse
            </div>
            
            <div class="mt-16 bg-gray-50 rounded-xl shadow-sm p-8 border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <div class="w-8 h-8 rounded-md bg-indigo-100 flex items-center justify-center text-indigo-600 mr-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    ¿Qué puedes hacer en la sección de Aprendizaje?
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300">
                        <div class="w-16 h-16 rounded-md bg-blue-100 flex items-center justify-center text-blue-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Videos Explicativos</h3>
                        <p class="text-gray-700 leading-relaxed">Accede a videos detallados que explican paso a paso la resolución de problemas y ejercicios complejos, adaptados al nivel universitario.</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300">
                        <div class="w-16 h-16 rounded-md bg-green-100 flex items-center justify-center text-green-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Toma Apuntes</h3>
                        <p class="text-gray-700 leading-relaxed">Guarda tus propios apuntes mientras ves los videos para facilitar tu estudio posterior. Organízalos por tema y accede a ellos cuando los necesites.</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition duration-300">
                        <div class="w-16 h-16 rounded-md bg-purple-100 flex items-center justify-center text-purple-600 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Comprensión Profunda</h3>
                        <p class="text-gray-700 leading-relaxed">Refuerza tu entendimiento viendo la explicación completa de cada concepto y problema. Supera tus obstáculos de aprendizaje con explicaciones claras.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>