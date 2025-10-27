<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header y botón de crear -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Artículos y Recomendaciones</h1>
                @auth
                    <a href="{{ route('blog.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Crear Artículo
                    </a>
                @endauth
            </div>

            <!-- Mensajes de éxito -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Grid de artículos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($posts as $post)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-all duration-300">
                        @if($post->image)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-all duration-500 hover:scale-105">
                            </div>
                        @else
                            <div class="h-48 bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <div class="p-5">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">{{ $post->published_at->format('d/m/Y') }}</span>
                                <span class="text-sm text-gray-500">Por {{ $post->author->name }}</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $post->title }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $post->excerpt ?? Str::limit(strip_tags($post->content), 150) }}</p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                Leer más →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <p class="text-gray-500">No hay artículos publicados actualmente.</p>
                        @auth
                            <p class="mt-4">
                                <a href="{{ route('blog.create') }}" class="text-indigo-600 hover:text-indigo-800">Crea el primer artículo</a>
                            </p>
                        @endauth
                    </div>
                @endforelse
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $posts->links() }}
            </div>

            <!-- Sección explicativa -->
            <div class="mt-12 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">¿Qué puedes encontrar en nuestro Blog?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex">
                        <div class="mr-4">
                            <div class="bg-indigo-100 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Artículos Educativos</h3>
                            <p class="text-gray-600">Contenido especializado para preparar tus exámenes de admisión universitarios.</p>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="mr-4">
                            <div class="bg-green-100 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Consejos de Estudio</h3>
                            <p class="text-gray-600">Recomendaciones y técnicas efectivas para maximizar tu tiempo de estudio.</p>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="mr-4">
                            <div class="bg-amber-100 p-3 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-800 mb-2">Experiencias y Testimonios</h3>
                            <p class="text-gray-600">Historias de éxito y consejos de estudiantes que lograron ingresar a la universidad.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos adicionales -->
    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>