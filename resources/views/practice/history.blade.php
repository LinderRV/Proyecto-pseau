<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Historial de Prácticas</h1>
            
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-4 border-b flex flex-wrap gap-3 items-center">
                    <h2 class="text-lg font-medium text-gray-800 mr-auto">Filtrar resultados</h2>
                    
                    <div>
                        <label for="course_filter" class="sr-only">Curso</label>
                        <select id="course_filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Todos los cursos</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puntuación</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correctas</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tiempo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($practiceResults as $result)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $result->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $result->course->name }}
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
                                {{ formatTime($result->time_taken) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('practice.detail', ['id' => $result->id]) }}" class="text-indigo-600 hover:text-indigo-900">Ver detalles</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-6">
                {{ $practiceResults->links() }}
            </div>
            
            <!-- Gráfico de progreso por materia -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Progreso por Materias</h2>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="space-y-4">
                        @foreach($courseProgress as $progress)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <div class="text-sm font-medium text-gray-700">{{ $progress['course']->name }}</div>
                                <div class="text-sm font-medium text-gray-700">{{ number_format($progress['avg_score'], 1) }}%</div>
                            </div>
                            <div class="relative w-full h-3 bg-gray-200 rounded-full">
                                <div class="absolute top-0 left-0 h-3 
                                    @if($progress['avg_score'] >= 80) bg-green-500 
                                    @elseif($progress['avg_score'] >= 60) bg-yellow-500 
                                    @else bg-red-500 
                                    @endif rounded-full" 
                                    style="width: {{ $progress['avg_score'] }}%">
                                </div>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">{{ $progress['total_practices'] }} sesiones de práctica</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Estadísticas de rendimiento -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Temas con mejor rendimiento</h2>
                    <div class="space-y-6">
                        @foreach($bestTopics as $topic)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-green-500 mr-2"></div>
                                    <span class="text-sm text-gray-800">{{ $topic['name'] }}</span>
                                </div>
                                <span class="text-sm text-green-600 font-medium">{{ number_format($topic['score'], 1) }}%</span>
                            </div>
                            <div class="relative w-full h-2 bg-gray-200 rounded-full">
                                <div class="absolute top-0 left-0 h-2 bg-green-500 rounded-full" style="width: {{ $topic['score'] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-800 mb-4">Temas a mejorar</h2>
                    <div class="space-y-6">
                        @foreach($worstTopics as $topic)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full bg-red-500 mr-2"></div>
                                    <span class="text-sm text-gray-800">{{ $topic['name'] }}</span>
                                </div>
                                <span class="text-sm text-red-600 font-medium">{{ number_format($topic['score'], 1) }}%</span>
                            </div>
                            <div class="relative w-full h-2 bg-gray-200 rounded-full">
                                <div class="absolute top-0 left-0 h-2 bg-red-500 rounded-full" style="width: {{ $topic['score'] }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseFilter = document.getElementById('course_filter');
        const dateFilter = document.getElementById('date_filter');
        
        // Function to apply filters
        function applyFilters() {
            const courseId = courseFilter.value;
            const dateRange = dateFilter.value;
            
            // Create URL with query parameters
            let url = new URL(window.location.href);
            
            // Reset pagination when filters change
            url.searchParams.delete('page');
            
            // Add filter parameters if they have values
            if (courseId) {
                url.searchParams.set('course_id', courseId);
            } else {
                url.searchParams.delete('course_id');
            }
            
            if (dateRange) {
                url.searchParams.set('date_range', dateRange);
            } else {
                url.searchParams.delete('date_range');
            }
            
            // Navigate to the filtered URL
            window.location.href = url.toString();
        }
        
        // Add event listeners for filter changes
        courseFilter.addEventListener('change', applyFilters);
        dateFilter.addEventListener('change', applyFilters);
        
        // Set initial filter values from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const courseParam = urlParams.get('course_id');
        const dateParam = urlParams.get('date_range');
        
        if (courseParam) {
            courseFilter.value = courseParam;
        }
        
        if (dateParam) {
            dateFilter.value = dateParam;
        }
    });
    </script>
</x-app-layout>