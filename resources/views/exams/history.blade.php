<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Historial de Exámenes</h1>
            
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-4 border-b flex flex-wrap gap-3 items-center">
                    <h2 class="text-lg font-medium text-gray-800 mr-auto">Filtrar resultados</h2>
                    
                    <div>
                        <label for="university_filter" class="sr-only">Universidad</label>
                        <select id="university_filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Todas las universidades</option>
                            @foreach($universities as $university)
                                <option value="{{ $university->id }}">{{ $university->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="date_filter" class="sr-only">Fecha</label>
                        <select id="date_filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Todas las fechas</option>
                            <option value="7">Últimos 7 días</option>
                            <option value="30">Últimos 30 días</option>
                            <option value="90">Últimos 3 meses</option>
                            <option value="365">Último año</option>
                        </select>
                    </div>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Universidad</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puntuación</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correctas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Incorrectas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sin Responder</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiempo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
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
                                {{ $result->incorrect_answers }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->unanswered }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->total_questions }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ formatTime($result->time_taken) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Ver detalles</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $examResults->links() }}
            </div>
            
            <!-- Estadísticas de rendimiento -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Puntuación Promedio</h2>
                    <div class="flex items-center">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center 
                            @if($stats['avg_score'] >= 80) bg-green-100 text-green-800 
                            @elseif($stats['avg_score'] >= 60) bg-yellow-100 text-yellow-800 
                            @else bg-red-100 text-red-800 
                            @endif">
                            <span class="text-xl font-bold">{{ number_format($stats['avg_score'], 0) }}%</span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Basado en {{ $stats['total_exams'] }} exámenes</p>
                            <p class="text-sm text-gray-500">
                                @if($stats['trend'] > 0)
                                    <span class="text-green-600">↑ {{ number_format(abs($stats['trend']), 1) }}%</span> mejor que el mes anterior
                                @elseif($stats['trend'] < 0)
                                    <span class="text-red-600">↓ {{ number_format(abs($stats['trend']), 1) }}%</span> peor que el mes anterior
                                @else
                                    Igual que el mes anterior
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Temas con mejor rendimiento</h2>
                    <ul class="space-y-3">
                        @foreach($stats['best_subjects'] as $subject)
                        <li class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                            <span class="text-gray-800">{{ $subject['name'] }}</span>
                            <span class="ml-auto text-green-600 font-medium">{{ number_format($subject['score'], 1) }}%</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Temas a mejorar</h2>
                    <ul class="space-y-3">
                        @foreach($stats['worst_subjects'] as $subject)
                        <li class="flex items-center">
                            <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                            <span class="text-gray-800">{{ $subject['name'] }}</span>
                            <span class="ml-auto text-red-600 font-medium">{{ number_format($subject['score'], 1) }}%</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>