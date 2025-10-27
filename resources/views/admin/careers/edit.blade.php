<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold mb-4">Editar Carrera</h2>

                    <form action="{{ route('admin.careers.update', $career) }}" method="POST">@csrf @method('PUT')
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $career->name) }}" class="mt-1 block w-full border border-gray-200 rounded-md px-3 py-2" required>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Universidad (opcional)</label>
                            <select name="university_id" class="mt-1 block w-full border border-gray-200 rounded-md px-3 py-2">
                                <option value="">-- Ninguna --</option>
                                @foreach($universities as $u)
                                    <option value="{{ $u->id }}" {{ $career->university_id == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <button class="px-4 py-2 bg-indigo-600 text-white rounded-md">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>