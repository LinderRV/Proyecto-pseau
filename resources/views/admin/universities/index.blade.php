<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-800">Universidades</h2>
                            <p class="text-gray-600">Gestiona las universidades</p>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="openCreateUniversityModal()" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700">Crear Universidad</button>
                            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-200">Volver</a>
                        </div>
                    </div>

                    @if(session('success'))
                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">{{ session('success') }}</div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($universities as $u)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $u->id }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $u->name }}</td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <button type="button" data-id="{{ $u->id }}" data-name="{{ $u->name }}" onclick="openEditUniversityModal(this)" class="inline-flex items-center justify-center w-8 h-8 rounded-md hover:bg-indigo-100 text-blue-600 hover:text-blue-900" aria-label="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('admin.universities.destroy', $u) }}" method="POST" class="inline-block" data-confirm="Confirmar eliminación">@csrf @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-md text-red-600 hover:text-red-900" aria-label="Eliminar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $universities->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create University Modal -->
    <div id="createUniversityModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-medium mb-4">Crear Universidad</h3>
            <form action="{{ route('admin.universities.store') }}" method="POST">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" value="" class="mt-1 block w-full border border-gray-200 rounded-md px-3 py-2" required>
                </div>
                <div class="mt-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateUniversityModal()" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Crear</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit University Modal -->
    <div id="editUniversityModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-medium mb-4">Editar Universidad</h3>
            <form id="editUniversityForm" method="POST">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" id="editUniversityName" value="" class="mt-1 block w-full border border-gray-200 rounded-md px-3 py-2" required>
                </div>
                <div class="mt-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeEditUniversityModal()" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateUniversityModal() {
            document.getElementById('createUniversityModal').classList.remove('hidden');
            document.getElementById('createUniversityModal').classList.add('flex');
        }
        function closeCreateUniversityModal() {
            document.getElementById('createUniversityModal').classList.remove('flex');
            document.getElementById('createUniversityModal').classList.add('hidden');
        }
        function openEditUniversityModal(btn) {
            var id = btn.getAttribute('data-id');
            var name = btn.getAttribute('data-name');
            document.getElementById('editUniversityName').value = name;
            var form = document.getElementById('editUniversityForm');
            form.action = '/admin/universities/' + id;
            document.getElementById('editUniversityModal').classList.remove('hidden');
            document.getElementById('editUniversityModal').classList.add('flex');
        }
        function closeEditUniversityModal() {
            document.getElementById('editUniversityModal').classList.remove('flex');
            document.getElementById('editUniversityModal').classList.add('hidden');
        }
    </script>
</x-app-layout>