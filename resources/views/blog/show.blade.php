<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensaje de éxito -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Encabezado y navegación -->
            <div class="mb-6">
                <a href="{{ route('blog.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver al Blog
                </a>
            </div>

            <!-- Artículo -->
            <article class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Imagen de portada -->
                @if($post->image)
                    <div class="h-80 overflow-hidden">
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-6">
                    <!-- Meta información -->
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-500">{{ $post->published_at->format('d/m/Y') }}</span>
                        <span class="text-sm text-gray-500">Por {{ $post->author->name }}</span>
                    </div>

                    <!-- Título -->
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ $post->title }}</h1>

                    <!-- Excerpt si existe -->
                    @if($post->excerpt)
                        <div class="text-lg text-gray-600 mb-6 italic">
                            {{ $post->excerpt }}
                        </div>
                    @endif

                    <!-- Contenido -->
                    <div class="prose max-w-none">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <!-- Acciones para el autor -->
                    @if(auth()->id() == $post->user_id || auth()->user()->is_admin ?? false)
                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end space-x-3">
                            <a href="{{ route('blog.edit', $post->slug) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Editar
                            </a>

                            <form method="POST" action="{{ route('blog.destroy', $post->slug) }}" data-confirm="¿Estás seguro que deseas eliminar este artículo?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </div>
</x-app-layout>