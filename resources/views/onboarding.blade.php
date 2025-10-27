<x-guest-layout>
    <div class="form-container">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">¡Completa tu perfil!</h2>
        <p class="text-gray-600 mb-8 text-center">Para ofrecerte recomendaciones personalizadas, necesitamos conocer la universidad y la carrera que te interesan.</p>

        <form method="POST" action="{{ route('onboarding.store') }}">
            @csrf
            
            <!-- Universidad -->
            <div class="mb-6">
                <label for="university" class="block text-sm font-medium text-gray-700 mb-2">Universidad a la que planeas postular</label>
                <select id="university_id" name="university_id" class="form-input" required>
                    <option value="" disabled selected>Selecciona una universidad</option>
                    @foreach($universities as $university)
                        <option value="{{ $university->id }}">{{ $university->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('university_id')" class="mt-2" />
            </div>
            
            <!-- Carrera -->
            <div class="mb-6">
                <label for="career" class="block text-sm font-medium text-gray-700 mb-2">Carrera que te interesa</label>
                <select id="career_id" name="career_id" class="form-input" required>
                    <option value="" disabled selected>Selecciona una carrera</option>
                    @foreach($careers as $career)
                        <option value="{{ $career->id }}">{{ $career->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('career_id')" class="mt-2" />
            </div>
            
            <!-- Cursos recomendados (se llenarán dinámicamente) -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cursos recomendados para tu carrera</label>
                <div id="recommended-courses" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="text-center text-gray-500 py-4 col-span-2">
                        <p>Selecciona una carrera para ver los cursos recomendados</p>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-full">
                Continuar al Dashboard
            </button>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const careerSelect = document.getElementById('career_id');
            const coursesContainer = document.getElementById('recommended-courses');
            
            careerSelect.addEventListener('change', function() {
                const careerId = this.value;
                
                if (!careerId) {
                    coursesContainer.innerHTML = '<div class="text-center text-gray-500 py-4 col-span-2"><p>Selecciona una carrera para ver los cursos recomendados</p></div>';
                    return;
                }
                
                // Show loading
                coursesContainer.innerHTML = '<div class="text-center text-gray-500 py-4 col-span-2"><p>Cargando cursos recomendados...</p></div>';
                
                // Fetch recommended courses
                fetch(`/onboarding/recommended-courses?career_id=${careerId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.courses.length === 0) {
                            coursesContainer.innerHTML = '<div class="text-center text-gray-500 py-4 col-span-2"><p>No hay cursos recomendados para esta carrera</p></div>';
                            return;
                        }
                        
                        // Generate course cards
                        let html = '';
                        data.courses.forEach(course => {
                            const importanceWidth = course.importance * 10; // Convert 1-10 to percentage
                            
                            html += `
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-2">
                                        <div class="p-2 rounded-lg" style="background-color: ${course.color}20">
                                            <svg class="h-5 w-5" style="color: ${course.color}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <h3 class="ml-2 text-sm font-medium text-gray-700">${course.name}</h3>
                                    </div>
                                    <div class="relative w-full h-1 bg-gray-200 rounded-full">
                                        <div class="absolute top-0 left-0 h-1 rounded-full" style="background-color: ${course.color}; width: ${importanceWidth}%"></div>
                                    </div>
                                    <div class="mt-1 flex justify-between text-xs">
                                        <span class="font-medium text-gray-700">Importancia: ${course.importance}/10</span>
                                    </div>
                                </div>
                            `;
                        });
                        
                        coursesContainer.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        coursesContainer.innerHTML = '<div class="text-center text-gray-500 py-4 col-span-2"><p>Error al cargar los cursos recomendados</p></div>';
                    });
            });
        });
    </script>
</x-guest-layout>