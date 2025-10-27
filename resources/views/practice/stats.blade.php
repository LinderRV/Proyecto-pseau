<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gráficos Estadísticos</h2>
    </x-slot>

    <div class="py-8 px-4 md:px-8">
        <div class="max-w-7xl mx-auto">
            @php
            $best = collect($courseAverages ?? [])->sortByDesc('avg_score')->first();
            $worst = collect($courseAverages ?? [])->sortBy('avg_score')->first();
            @endphp
            <!-- Summary cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm flex items-center space-x-4">
                    <div class="p-3 bg-blue-50 rounded-md">
                        <!-- Graduation cap -->
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422A12.083 12.083 0 0118 20.25M12 14L5.84 10.578A12.083 12.083 0 006 20.25" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Promedio General</p>
                        <p class="text-2xl font-bold mt-1" data-overall-avg>{{ $overallAvg ?? 0 }}%</p>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm flex items-center space-x-4">
                    <div class="p-3 bg-white rounded-md border border-gray-100">
                        <!-- Star -->
                        <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.966a1 1 0 00.95.69h4.18c.969 0 1.371 1.24.588 1.81l-3.386 2.46a1 1 0 00-.364 1.118l1.287 3.967c.3.922-.755 1.688-1.538 1.118L10 15.348l-3.453 2.708c-.783.57-1.838-.196-1.538-1.118l1.287-3.967a1 1 0 00-.364-1.118L2.543 9.393c-.783-.57-.38-1.81.588-1.81h4.18a1 1 0 00.95-.69L9.05 2.927z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Mejor Curso</p>
                        <p class="text-lg font-semibold mt-1">{{ $best['course'] ?? '—' }} @if($best) <span class="text-sm text-gray-400">({{ $best['avg_score'] }}%)</span> @endif</p>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm flex items-center space-x-4">
                    <div class="p-3 bg-yellow-50 rounded-md">
                        <!-- Warning triangle -->
                        <svg class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v4m0 4h.01" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Curso a Mejorar</p>
                        <p class="text-lg font-semibold mt-1">{{ $worst['course'] ?? '—' }} @if($worst) <span class="text-sm text-gray-400">({{ $worst['avg_score'] }}%)</span> @endif</p>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm flex items-center space-x-4">
                    <div class="p-3 bg-blue-50 rounded-md">
                        <!-- Clipboard -->
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 2h6a2 2 0 012 2v1H7V4a2 2 0 012-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h10v13a2 2 0 01-2 2H9a2 2 0 01-2-2V7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Exámenes Completados</p>
                        <p class="text-2xl font-bold mt-1">{{ count($examScores ?? []) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <p class="text-gray-600 mb-4">Resumen de tu rendimiento por curso y exámenes recientes.</p>

                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1 bg-gray-50 p-4 rounded h-80 flex flex-col overflow-hidden">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium">Promedio por curso</h3>
                            <div class="flex items-center space-x-2">
                                <select id="orderSelect" class="form-select">
                                    <option value="none">Ordenar</option>
                                    <option value="desc">Mayor primero</option>
                                    <option value="asc">Menor primero</option>
                                </select>
                                <button id="exportCoursesCsv" class="btn btn-outline">Exportar CSV</button>
                            </div>
                        </div>
                        <div class="flex-1 min-h-0">
                            <div class="w-full h-full flex items-center">
                                <!-- make canvas fill container but respect max-height -->
                                <canvas id="chartCourses" class="w-full h-full" style="display:block; max-height:100%;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="w-96 bg-gray-50 p-4 rounded h-80 flex flex-col overflow-hidden">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-sm font-medium">Puntajes de exámenes recientes</h3>
                            <div class="flex items-center space-x-2">
                                <select id="dateRangeSelect" class="form-select">
                                    <option value="0">Últimos: Todo</option>
                                    <option value="7">Última semana</option>
                                    <option value="30">Último mes</option>
                                    <option value="90">Últimos 3 meses</option>
                                </select>
                                <select id="courseFilter" class="form-select">
                                    <option value="">Filtrar por curso</option>
                                    @foreach($courses as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex-1 min-h-0">
                            <div class="w-full h-full flex items-center">
                                <canvas id="chartExams" class="w-full h-full" style="display:block; max-height:100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        window.STATS_PAYLOAD = {
            courseAverages: {!! json_encode($courseAverages ?? []) !!},
            examScores: {!! json_encode($examScores ?? []) !!},
            perCourseHistory: {!! json_encode($perCourseHistory ?? []) !!},
            courses: {!! json_encode($courses ?? []) !!},
            overallAvg: {!! json_encode($overallAvg ?? 0) !!}
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="/js/statsCharts.js"></script>
</x-app-layout>