<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Mensaje de bienvenida personalizado -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8 hover-shadow-effect">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">¡Hola, {{ Auth::user()->name }}! Listo para practicar?</h1>
                        <p class="text-gray-600 mt-1">
                            @if($user->career)
                            Preparando para <span class="font-semibold">{{ $user->career->name }}</span>
                            @if($user->university)
                            en <span class="font-semibold">{{ $user->university->name }}</span>
                            @endif
                            @else
                            Continúa preparándote para tu examen de admisión.
                            @endif
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('exams.index') }}" class="btn btn-primary inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                            Iniciar Examen Simulado
                        </a>
                    </div>
                </div>
            </div>

            <!-- Áreas de Estudio -->
            <div class="flex justify-end items-center mb-4">
                <a href="{{ route('practice.stats') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Graficos Estadísticos →</a>
            </div>
            <!-- Estadísticas de Exámenes -->
            @if(isset($examStats) && $examStats['total'] > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8 hover-shadow-effect">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Resumen de Exámenes</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4 text-center">
                        <p class="text-sm text-blue-600 uppercase font-semibold">Total de Exámenes</p>
                        <p class="text-3xl font-bold text-blue-700">{{ $examStats['total'] }}</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-4 text-center">
                        <p class="text-sm text-green-600 uppercase font-semibold">Puntuación Promedio</p>
                        <p class="text-3xl font-bold text-green-700">{{ number_format($examStats['avg_score'], 1) }}%</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 text-center">
                        <p class="text-sm text-purple-600 uppercase font-semibold">Puntuación Más Alta</p>
                        <p class="text-3xl font-bold text-purple-700">{{ number_format($examStats['highest_score'], 1) }}%</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Áreas de Estudio -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Áreas de Estudio</h2>
                <a href="{{ route('practice.history') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Ver historial de prácticas →</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                @foreach($practiceResultsBySubject as $courseId => $subjectData)
                <!-- {{ $subjectData['course']->name }} -->
                <div class="bg-white rounded-lg shadow-md p-6 hover-shadow-effect">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg {{ getCourseColor($courseId, 'bg') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 {{ getCourseColor($courseId, 'text') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    @if($courseId == 1)
                                    <!-- Matemáticas - calculate -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    @elseif($courseId == 2 || $courseId == 3)
                                    <!-- Física/Química - science -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    @elseif($courseId == 4)
                                    <!-- Biología - biotech -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                    @elseif($courseId == 5)
                                    <!-- Historia - history_edu -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    @elseif($courseId == 6)
                                    <!-- Literatura - menu_book -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    @elseif($courseId == 7)
                                    <!-- Razonamiento Verbal - spellcheck -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    @elseif($courseId == 8)
                                    <!-- Razonamiento Matemático - psychology -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    @else
                                    <!-- Default icon for other courses -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    @endif
                                </svg>
                            </div>
                            <h3 class="ml-3 text-lg font-medium text-gray-800">{{ $subjectData['course']->name }}</h3>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="relative w-full h-2 bg-gray-200 rounded-full">
                            <div class="absolute top-0 left-0 h-2 {{ getCourseColor($courseId, 'bg-full') }} rounded-full"
                                style="width: {{ $subjectData['progress'] }}%">
                            </div>
                        </div>
                        <div class="mt-2 flex justify-between items-center text-sm">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-700">
                                    @if($subjectData['total_practices'] > 0)
                                    {{ number_format($subjectData['avg_score'], 1) }}% Dominio
                                    @else
                                    Sin práctica
                                    @endif
                                </span>

                                @if($subjectData['total_practices'] > 0)
                                <!-- Mini indicador visual -->
                                <div class="w-16 h-1.5 bg-gray-200 rounded-full mt-1">
                                    <div class="h-1.5 rounded-full 
                                            @if($subjectData['avg_score'] >= 80) bg-green-500 
                                            @elseif($subjectData['avg_score'] >= 60) bg-yellow-500 
                                            @else bg-red-500 
                                            @endif"
                                        style="width: {{ min($subjectData['avg_score'], 100) }}%">
                                    </div>
                                </div>
                                @endif
                            </div>
                            @if($subjectData['total_practices'] > 0)
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-500">{{ $subjectData['total_practices'] }} sesiones</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('practice.course', ['course' => $courseId]) }}" class="btn btn-outline w-full {{ getCourseColor($courseId, 'bg-light') }} {{ getCourseColor($courseId, 'hover') }} {{ getCourseColor($courseId, 'text') }} border-{{ explode('-', getCourseColor($courseId, 'border'))[1] }}-200">
                        Practicar
                    </a>
                </div>
                @endforeach

            </div>

            <!-- Exámenes Recientes -->
            @if(isset($examResults) && $examResults->count() > 0)
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Exámenes Recientes</h2>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Universidad</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puntuación</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correctas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiempo</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($examResults as $result)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $result->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $result->university ? $result->university->name : 'General' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($result->score >= 80) bg-green-100 text-green-800 
                                        @elseif($result->score >= 60) bg-yellow-100 text-yellow-800 
                                        @else bg-red-100 text-red-800 
                                        @endif">
                                        {{ number_format($result->score, 1) }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $result->correct_answers }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $result->total_questions }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $result->time_taken }} min
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if(count($examResults) >= 5)
                <div class="mt-3 text-right">
                    <a href="{{ route('exams.history') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Ver todos los exámenes →</a>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>