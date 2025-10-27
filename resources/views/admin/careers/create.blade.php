<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold mb-4">Crear Carrera</h2>

                    <form action="{{ route('admin.careers.store') }}" method="POST">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full border border-gray-200 rounded-md px-3 py-2" required>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Universidad (opcional)</label>
                            <select name="university_id" class="mt-1 block w-full border border-gray-200 rounded-md px-3 py-2">
                                <option value="">-- Ninguna --</option>
                                @foreach($universities as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4 flex items-center space-x-3">
                            <a href="{{ route('admin.careers.index') }}" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-200">Volver</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>