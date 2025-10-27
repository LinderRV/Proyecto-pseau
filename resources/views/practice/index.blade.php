<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Practicar por Materia</h1>
            <p class="text-gray-600 mb-8">Selecciona una materia para comenzar a practicar preguntas específicas.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($courses as $course)
                    <a href="{{ route('practice.course', $course->id) }}" class="subject-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="p-2 rounded-lg" style="background-color: {{ $course->color }}20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" style="color: {{ $course->color }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <h3 class="ml-3 text-lg font-medium text-gray-800">{{ $course->name }}</h3>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $course->description }}</p>
                        <div class="text-blue-600 font-medium text-sm">
                            Comenzar práctica
                        </div>
                    </a>
                @endforeach
            </div>
            
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-6 mt-10">
                <h3 class="text-lg font-semibold text-blue-800 mb-3">Consejo para estudiar eficientemente</h3>
                <p class="text-blue-700 mb-3">La práctica constante y enfocada en temas específicos es una de las mejores formas de prepararte para tu examen de admisión.</p>
                <ul class="list-disc pl-5 text-blue-600 space-y-2">
                    <li>Dedica más tiempo a los temas que encuentres más difíciles</li>
                    <li>Revisa siempre las explicaciones de las preguntas incorrectas</li>
                    <li>Realiza sesiones de estudio cortas pero frecuentes (25-30 minutos)</li>
                    <li>Alterna entre diferentes materias para mantener tu mente activa</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>