<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Análisis de Dificultad de Cursos</h2>
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Volver al Dashboard
                        </a>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Dificultad de Cursos por Rendimiento</h3>
                        <p class="text-gray-600 mb-4">Los cursos están ordenados del más difícil al más fácil basado en el puntaje promedio.</p>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Curso
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Intentos
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Puntaje Promedio
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tiempo Promedio (min)
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Dificultad
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($courseDifficulty as $course)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $course->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $course->attempt_count }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($course->avg_score, 1) }}%
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($course->avg_time, 1) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $scoreValue = $course->avg_score;
                                                    $difficultyClass = 'bg-red-100 text-red-800';
                                                    $difficultyText = 'Alta';
                                                    
                                                    if ($scoreValue > 70) {
                                                        $difficultyClass = 'bg-green-100 text-green-800';
                                                        $difficultyText = 'Baja';
                                                    } elseif ($scoreValue > 50) {
                                                        $difficultyClass = 'bg-yellow-100 text-yellow-800';
                                                        $difficultyText = 'Media';
                                                    }
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $difficultyClass }}">
                                                    {{ $difficultyText }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No hay datos suficientes para mostrar la dificultad de los cursos.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Preguntas con Mayor Dificultad</h3>
                        <p class="text-gray-600 mb-4">Las 10 preguntas con la tasa de éxito más baja (ordenadas de la más difícil a la más fácil).</p>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pregunta
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Curso
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Intentos
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Correctas
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tasa de Éxito
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($difficultQuestions as $question)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ Str::limit($question->question_text, 60) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $question->course_name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $question->attempt_count }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $question->correct_count }} / {{ $question->attempt_count }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @php
                                                    $successRate = $question->success_rate;
                                                    $rateClass = 'bg-red-100 text-red-800';
                                                    
                                                    if ($successRate > 70) {
                                                        $rateClass = 'bg-green-100 text-green-800';
                                                    } elseif ($successRate > 40) {
                                                        $rateClass = 'bg-yellow-100 text-yellow-800';
                                                    }
                                                @endphp
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rateClass }}">
                                                    {{ number_format($successRate, 1) }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No hay datos suficientes para mostrar preguntas difíciles.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recomendaciones para Mejorar</h3>
                        <div class="bg-blue-50 p-4 rounded-md">
                            <ul class="list-disc list-inside text-gray-700 space-y-2">
                                <li>
                                    <span class="font-medium">Para cursos con dificultad alta:</span> Considere crear más materiales de estudio, tutoriales en video o sesiones de repaso.
                                </li>
                                <li>
                                    <span class="font-medium">Para las preguntas más difíciles:</span> Revise su redacción, asegúrese de que son claras y que el material necesario para responderlas está cubierto en el curso.
                                </li>
                                <li>
                                    <span class="font-medium">Si el tiempo promedio es alto:</span> Los estudiantes podrían estar teniendo dificultades para comprender el material. Considere simplificar o proporcionar explicaciones adicionales.
                                </li>
                                <li>
                                    <span class="font-medium">Monitoree regularmente:</span> Las tendencias de rendimiento pueden cambiar con el tiempo a medida que se agregan nuevos materiales de estudio.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>