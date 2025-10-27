<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">{{ $course->name }} - Práctica</h1>
                <div class="text-sm font-medium bg-blue-100 text-blue-800 py-1 px-3 rounded-full">
                    Pregunta {{ $current }} de {{ $total }}
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <!-- Question text -->
                <div class="mb-6">
                    <p class="text-gray-800 text-lg">{{ $question->question_text }}</p>
                    
                    @if($question->image)
                        <div class="mt-4">
                            <div class="flex justify-center">
                                <img src="{{ asset('storage/' . $question->image) }}" alt="Imagen de la pregunta" class="w-28 h-auto rounded-md shadow-sm mx-auto">
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Options -->
                <form id="answer-form" method="POST" action="{{ route('practice.submit') }}" autocomplete="off">
                    @csrf
                    
                    @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 mb-4">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <div class="space-y-3 mb-8">
                        @foreach($question->options as $option)
                            <label class="option-item flex items-start p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="option_id" value="{{ $option->id }}" class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" required>
                                <span class="ml-3 text-gray-700">{{ $option->option_text }}</span>
                            </label>
                        @endforeach
                    </div>
                    
                    <div class="flex justify-center">
                        <button type="submit" id="submit-answer-btn" class="btn btn-primary px-8 py-3">
                            <span>Enviar Respuesta</span>
                            <span id="loading-indicator" class="hidden ml-2 inline-flex items-center">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Progress bar -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Progreso</h3>
                <div class="relative w-full h-2 bg-gray-200 rounded-full">
                    <div class="absolute top-0 left-0 h-2 bg-blue-500 rounded-full" style="width: {{ ($current / $total) * 100 }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>{{ $current }} / {{ $total }}</span>
                    <span>{{ round(($current / $total) * 100) }}%</span>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Debug form submission
            const form = document.getElementById('answer-form');
            const submitBtn = document.getElementById('submit-answer-btn');
            let isSubmitting = false;
            
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submission triggered');
                    
                    // Prevent multiple submissions
                    if (isSubmitting) {
                        console.log('Form already submitting, preventing duplicate submission');
                        e.preventDefault();
                        return false;
                    }
                    
                    // Check if any option is selected
                    const selectedOption = form.querySelector('input[name="option_id"]:checked');
                    if (!selectedOption) {
                        e.preventDefault();
                        alert('Por favor selecciona una opción antes de enviar.');
                        return false;
                    }
                    
                    console.log('Selected option:', selectedOption.value);
                    // Mark as submitting to prevent duplicate submissions
                    isSubmitting = true;
                    submitBtn.disabled = true;
                    document.getElementById('loading-indicator').classList.remove('hidden');
                    
                    // Add extra logging to track form submission
                    console.log('Form validated, submitting now');
                    
                    // Form submission will continue naturally
                });
            }
            
            // Add a direct link to submit the form by pressing Enter on any option
            document.querySelectorAll('input[name="option_id"]').forEach(radio => {
                radio.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        if (!isSubmitting) {
                            form.submit();
                        }
                    }
                });
            });
            
            // Add selected class to option when clicked
            document.querySelectorAll('.option-item').forEach(item => {
                item.addEventListener('click', function() {
                    document.querySelectorAll('.option-item').forEach(el => {
                        el.classList.remove('selected');
                    });
                    this.classList.add('selected');
                    // Check the radio inside this label
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                    }
                });
            });
            
            // Select the option if radio is checked (e.g. via keyboard)
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.option-item').forEach(el => {
                        el.classList.remove('selected');
                    });
                    this.closest('.option-item').classList.add('selected');
                });
            });
        });
    </script>
</x-app-layout>