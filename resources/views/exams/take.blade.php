<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header with timer -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2 md:mb-0">Simulación de Examen de Admisión</h1>
                <div class="bg-white shadow rounded-lg px-4 py-2 flex items-center text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Tiempo restante: <span id="timer" class="ml-2 font-semibold">00:00:00</span>
                </div>
            </div>
            
            <form id="exam-form" method="POST" action="{{ route('exams.submit') }}">
                @csrf
                
                <!-- Questions -->
                @foreach($questions as $index => $question)
                    <div id="question-{{ $index+1 }}" class="question-container bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Pregunta {{ $index+1 }} / {{ $questions->count() }}</h3>
                            <span class="px-3 py-1 bg-{{ $question->course->color ? strtolower($question->course->color) . '-100' : 'gray-100' }} text-{{ $question->course->color ? strtolower($question->course->color) . '-800' : 'gray-800' }} text-xs font-semibold rounded-full">{{ $question->course->name }}</span>
                        </div>
                        
                        <div class="mb-4 text-gray-700">
                            {{ $question->question_text }}
                            
                            @if($question->image)
                                <div class="mt-3">
                                    <div class="flex justify-center">
                                        <img src="{{ asset('storage/' . $question->image) }}" alt="Imagen de la pregunta" class="w-28 h-auto rounded-md shadow-sm mx-auto">
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="space-y-3 mb-4">
                            @foreach($question->options as $option)
                                <label class="option-item flex items-start p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-3 text-gray-700">{{ $option->option_text }}</span>
                                </label>
                            @endforeach
                        </div>
                        
                        <div class="flex justify-between mt-6">
                            @if($index > 0)
                                <button type="button" onclick="showQuestion({{ $index }})" class="px-4 py-2 text-blue-600 hover:text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Anterior
                                </button>
                            @else
                                <div></div>
                            @endif
                            
                            @if($index < $questions->count() - 1)
                                <button type="button" onclick="showQuestion({{ $index+2 }})" class="px-4 py-2 text-blue-600 hover:text-blue-800">
                                    Siguiente
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            @else
                                <div></div>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                <!-- Question navigation -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Navegación de preguntas</h3>
                    <div class="grid grid-cols-5 sm:grid-cols-10 gap-2">
                        @foreach($questions as $index => $question)
                            <button 
                                type="button" 
                                onclick="showQuestion({{ $index+1 }})" 
                                class="question-nav-btn h-10 w-10 flex items-center justify-center rounded-md border border-gray-300 hover:bg-gray-100"
                                data-question="{{ $index+1 }}"
                            >
                                {{ $index+1 }}
                            </button>
                        @endforeach
                    </div>
                    <div class="flex justify-end mt-4 space-x-2">
                        <span class="flex items-center text-xs text-gray-600">
                            <span class="block h-3 w-3 rounded-full border border-gray-300 mr-1"></span>
                            Sin responder
                        </span>
                        <span class="flex items-center text-xs text-gray-600">
                            <span class="block h-3 w-3 rounded-full bg-blue-500 mr-1"></span>
                            Respondido
                        </span>
                    </div>
                </div>
                
                <!-- Submit button -->
                <div class="flex justify-center">
                    <button type="button" onclick="confirmSubmit()" class="btn btn-primary px-8 py-3">
                        Finalizar y Enviar Examen
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Confirmation Modal -->
    <div id="confirmation-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">¿Seguro que quieres finalizar?</h3>
            <p class="text-gray-600 mb-6">Una vez que envíes el examen, no podrás volver a revisar o cambiar tus respuestas.</p>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
                    Cancelar
                </button>
                <button type="button" onclick="submitExam()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Finalizar Examen
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Variables for exam functionality
        const totalQuestions = {{ $questions->count() }};
        let currentQuestion = 1;
        let answeredQuestions = {};
        let examStartTime = '{{ $start_time->toIso8601String() }}'; // Use ISO format for consistent parsing
        let timerInterval;
        
        // Initialize the exam UI
        document.addEventListener('DOMContentLoaded', function() {
            // Show only the first question initially
            showQuestion(1);
            
            // Log start time for debugging
            console.log('Exam start time:', examStartTime);
            
            // Set up the timer
            startTimer();
            
            // Add event listeners for radio buttons to track answered questions
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const questionId = this.name.match(/\d+/)[0];
                    const questionIndex = Array.from(document.querySelectorAll('.question-container')).findIndex(
                        q => q.querySelector(`input[name="answers[${questionId}]"]`)
                    );
                    
                    if (questionIndex !== -1) {
                        answeredQuestions[questionIndex + 1] = true;
                        updateQuestionNavigation();
                    }
                });
            });
        });
        
        // Show a specific question and hide others
        function showQuestion(questionNumber) {
            document.querySelectorAll('.question-container').forEach(function(question, index) {
                question.style.display = index + 1 === questionNumber ? 'block' : 'none';
            });
            currentQuestion = questionNumber;
            updateActiveQuestionNav();
        }
        
        // Update the active question in the navigation
        function updateActiveQuestionNav() {
            document.querySelectorAll('.question-nav-btn').forEach(btn => {
                btn.classList.remove('bg-gray-200');
                if (parseInt(btn.dataset.question) === currentQuestion) {
                    btn.classList.add('bg-gray-200');
                }
            });
        }
        
        // Update the navigation to show answered/unanswered questions
        function updateQuestionNavigation() {
            document.querySelectorAll('.question-nav-btn').forEach(btn => {
                const qNumber = parseInt(btn.dataset.question);
                if (answeredQuestions[qNumber]) {
                    btn.classList.add('bg-blue-500', 'text-white');
                } else {
                    btn.classList.remove('bg-blue-500', 'text-white');
                }
            });
        }
        
        // Timer functionality
        function startTimer() {
            const timerElement = document.getElementById('timer');
            const examDuration = 40; // 40 minutos para el examen
            
            timerInterval = setInterval(function() {
                const now = new Date();
                // Parse the ISO format date string directly
                const startDateTime = new Date(examStartTime);
                const elapsedTime = Math.floor((now - startDateTime) / 1000); // tiempo transcurrido en segundos
                const remainingTime = Math.max(0, (examDuration * 60) - elapsedTime); // tiempo restante en segundos
                
                // Log time information first time only (for debugging)
                if (!window.timerDebugLogged) {
                    console.log('Current time:', now);
                    console.log('Start time parsed:', startDateTime);
                    console.log('Elapsed seconds:', elapsedTime);
                    console.log('Remaining seconds:', remainingTime);
                    console.log('Exam duration (min):', examDuration);
                    window.timerDebugLogged = true;
                }
                
                const hours = Math.floor(remainingTime / 3600);
                const minutes = Math.floor((remainingTime % 3600) / 60);
                const seconds = remainingTime % 60;
                
                timerElement.textContent = 
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0');
                    
                // Si el tiempo se acabó, enviar automáticamente
                if (remainingTime <= 0) {
                    clearInterval(timerInterval);
                    alert('¡Se acabó el tiempo! Tu examen será enviado automáticamente.');
                    submitExam();
                }
            }, 1000);
        }
        
        // Confirmation modal functions
        function confirmSubmit() {
            document.getElementById('confirmation-modal').classList.remove('hidden');
        }
        
        function closeModal() {
            document.getElementById('confirmation-modal').classList.add('hidden');
        }
        
        function submitExam() {
            clearInterval(timerInterval);
            document.getElementById('exam-form').submit();
        }
    </script>
</x-app-layout>