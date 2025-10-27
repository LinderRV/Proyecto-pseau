<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800">Panel de Administración</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Usuarios -->
                        <div class="bg-blue-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-blue-600">Usuarios</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['users'] }}</p>
                                </div>
                                <div class="bg-blue-100 rounded-full p-3">
                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Cursos -->
                        <div class="bg-green-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-green-600">Cursos</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['courses'] }}</p>
                                </div>
                                <div class="bg-green-100 rounded-full p-3">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Preguntas -->
                        <div class="bg-purple-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-purple-600">Preguntas</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['questions'] }}</p>
                                </div>
                                <div class="bg-purple-100 rounded-full p-3">
                                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Exámenes -->
                        <div class="bg-yellow-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-yellow-600">Exámenes</p>
                                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['examResults'] }}</p>
                                </div>
                                <div class="bg-yellow-100 rounded-full p-3">
                                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Management Links -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Gestión</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <a href="{{ route('admin.universities.index') }}" class="bg-white border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition flex items-center">
                                <div class="bg-indigo-100 rounded-full p-3 mr-4">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Universidades</h4>
                                    <p class="text-sm text-gray-500 mt-1">Agregar y administrar universidades</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.careers.index') }}" class="bg-white border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition flex items-center">
                                <div class="bg-indigo-100 rounded-full p-3 mr-4">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Carreras</h4>
                                    <p class="text-sm text-gray-500 mt-1">Agregar y administrar carreras</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.courses.index') }}" class="bg-white border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition flex items-center">
                                <div class="bg-indigo-100 rounded-full p-3 mr-4">
                                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Gestionar Cursos</h4>
                                    <p class="text-sm text-gray-500 mt-1">Crear, editar y eliminar cursos</p>
                                </div>
                            </a>
                            
                            <a href="{{ route('admin.analytics.course-difficulty') }}" class="bg-white border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition flex items-center">
                                <div class="bg-green-100 rounded-full p-3 mr-4">
                                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">Analíticas de Dificultad</h4>
                                    <p class="text-sm text-gray-500 mt-1">Ver estadísticas de rendimiento por curso</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>