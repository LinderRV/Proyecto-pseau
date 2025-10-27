<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Artículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Navegación -->
            <div class="mb-6">
                <a href="{{ route('blog.show', $post->slug) }}" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Volver al Artículo
                </a>
            </div>

            <!-- Formulario de edición -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h1 class="text-2xl font-semibold text-gray-800 mb-6">Editar Artículo</h1>

                <form action="{{ route('blog.update', $post->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Título -->
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-medium mb-2">Título</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Extracto -->
                    <div class="mb-4">
                        <label for="excerpt" class="block text-gray-700 font-medium mb-2">Extracto (opcional)</label>
                        <textarea name="excerpt" id="excerpt" rows="3" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('excerpt', $post->excerpt) }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Un breve resumen que se mostrará en las tarjetas del blog.</p>
                        @error('excerpt')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contenido -->
                    <div class="mb-4">
                        <label for="content" class="block text-gray-700 font-medium mb-2">Contenido</label>
                        <textarea name="content" id="content" rows="15" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Imagen actual -->
                    @if($post->image)
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2">Imagen Actual</label>
                            <div class="w-40 h-40 overflow-hidden rounded-md shadow-sm">
                                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                    @endif

                    <!-- Nueva imagen -->
                    <div class="mb-4">
                        <label for="image" class="block text-gray-700 font-medium mb-2">{{ $post->image ? 'Cambiar Imagen (opcional)' : 'Imagen (opcional)' }}</label>
                        <input type="file" name="image" id="image" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <p class="text-sm text-gray-500 mt-1">Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.</p>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Publicar -->
                    <div class="mb-6">
                        <label for="published" class="inline-flex items-center">
                            <input type="checkbox" name="published" id="published" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('published', $post->published) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Publicado</span>
                        </label>
                        <p class="text-sm text-gray-500 mt-1">Desmarcar para guardar como borrador.</p>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end">
                        <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Actualizar Artículo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>