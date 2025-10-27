<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Resultados de Práctica</h1>
                        <p class="text-gray-600">{{ $results['course']->name }}</p>
                        {{-- video handled above inside the explanation block --}}
                        <div class="text-gray-700 text-lg font-semibold">{{ formatTime($results['timeTaken']) }}</div>
                        <div class="text-gray-600 text-sm">Tiempo utilizado</div>
                    </div>
                </div>

                <!-- Results graph -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Resumen de resultados</h2>
                    <div class="h-6 bg-gray-200 rounded-full overflow-hidden">
                        @if($results['totalQuestions'] > 0)
                        <div class="h-6 bg-green-500 rounded-l-full" style="width: {{ ($results['correctAnswers'] / $results['totalQuestions']) * 100 }}%; float: left;"></div>
                        <div class="h-6 bg-red-500" style="width: {{ (($results['totalQuestions'] - $results['correctAnswers']) / $results['totalQuestions']) * 100 }}%; float: left;"></div>
                        @endif
                    </div>
                    <div class="flex justify-between text-xs mt-2">
                        <div class="flex items-center">
                            <span class="h-3 w-3 bg-green-500 inline-block mr-1 rounded-full"></span>
                            <span>Correctas ({{ round(($results['correctAnswers'] / $results['totalQuestions']) * 100) }}%)</span>
                        </div>
                        <div class="flex items-center">
                            <span class="h-3 w-3 bg-red-500 inline-block mr-1 rounded-full"></span>
                            <span>Incorrectas ({{ round((($results['totalQuestions'] - $results['correctAnswers']) / $results['totalQuestions']) * 100) }}%)</span>
                        </div>
                    </div>
                </div>

                <!-- Performance analysis -->
                <div class="mb-8 p-4 rounded-lg {{ $results['score'] >= 80 ? 'bg-green-50 border border-green-100' : ($results['score'] >= 60 ? 'bg-yellow-50 border border-yellow-100' : 'bg-red-50 border border-red-100') }}">
                    <h3 class="text-lg font-semibold {{ $results['score'] >= 80 ? 'text-green-800' : ($results['score'] >= 60 ? 'text-yellow-800' : 'text-red-800') }} mb-2">
                        {{ $results['score'] >= 80 ? '¡Excelente trabajo!' : ($results['score'] >= 60 ? 'Buen trabajo, pero puedes mejorar' : 'Necesitas más práctica') }}
                    </h3>
                    <p class="{{ $results['score'] >= 80 ? 'text-green-700' : ($results['score'] >= 60 ? 'text-yellow-700' : 'text-red-700') }}">
                        @if($results['score'] >= 80)
                        Dominas muy bien este tema. Considera aumentar la dificultad o practicar temas más avanzados.
                        @elseif($results['score'] >= 60)
                        Tienes un buen nivel de conocimiento, pero aún hay áreas que puedes mejorar.
                        @else
                        Dedica más tiempo a este tema. Repasa los conceptos básicos y vuelve a intentarlo.
                        @endif
                    </p>
                </div>

                <h2 class="text-lg font-semibold text-gray-800 mb-4">Preguntas revisadas</h2>

                <!-- Question list accordion -->
                <div class="space-y-4 mb-8">
                    @foreach($results['questions'] as $index => $question)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="flex items-center justify-between p-4 {{ isset($question['is_correct']) && $question['is_correct'] ? 'bg-green-50' : 'bg-red-50' }}">
                            <h3 class="font-medium">Pregunta {{ $index + 1 }}</h3>
                            <span class="{{ isset($question['is_correct']) && $question['is_correct'] ? 'text-green-700' : 'text-red-700' }} font-medium">
                                {{ isset($question['is_correct']) && $question['is_correct'] ? 'Correcta' : 'Incorrecta' }}
                            </span>
                        </div>
                        <div class="p-4 bg-white">
                            <p class="text-gray-800 mb-3">{{ $question->question_text }}</p>
                            <p class="text-gray-800 mb-3">{{ $question->question_text }}</p>

                            @if(!empty($question->image))
                                <div class="mt-3">
                                    <div class="flex justify-center">
                                        <img src="{{ asset('storage/' . $question->image) }}" alt="Imagen de la pregunta" class="w-28 h-auto rounded-md shadow-sm mx-auto">
                                    </div>
                                </div>
                            @endif
                            @if(isset($question['selected_option']))
                            <div class="mb-2">
                                <span class="text-sm text-gray-600">Tu respuesta:</span>
                                <span class="ml-2 {{ isset($question['is_correct']) && $question['is_correct'] ? 'text-green-700' : 'text-red-700' }} font-medium">
                                    {{ $question['selected_option']->option_text }}
                                </span>
                            </div>
                            @endif

                            @if(isset($question['is_correct']) && !$question['is_correct'])
                            <div class="mb-2">
                                <span class="text-sm text-gray-600">Respuesta correcta:</span>
                                <span class="ml-2 text-green-700 font-medium">
                                    {{ $question->correctOption()->option_text }}
                                </span>
                            </div>
                            @endif

                            <div class="mt-3 pt-3 border-t border-gray-200 text-sm text-gray-700 flex items-start">
                                <div class="w-1/2">
                                    @if($question->explanation)
                                    <span class="font-medium">Explicación:</span>
                                    <p class="mt-1">{{ $question->explanation }}</p>
                                    @endif
                                </div>
                                <div class="w-1/2 flex justify-end items-start">
                                    {{-- video button will be placed here --}}
                                    @php
                                    // determine video url if available
                                    $videoUrl = null;
                                    if(!empty($question->video_url)) {
                                    $videoUrl = $question->video_url;
                                    } elseif(!empty($question->youtube_id)) {
                                    $videoUrl = 'https://www.youtube.com/watch?v=' . $question->youtube_id;
                                    }
                                    @endphp
                                    @if($videoUrl)
                                    <div class="flex items-center space-x-2">
                                        <button type="button" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center shadow-sm ml-4" aria-label="Ver video" onclick="openVideoWindow({{ json_encode($videoUrl) }}, {{ json_encode($question->id) }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M3 5.25C3 4.007 4.007 3 5.25 3h13.5C19.993 3 21 4.007 21 5.25v13.5c0 1.243-1.007 2.25-2.25 2.25H5.25C4.007 21 3 19.993 3 18.75V5.25zM10 8.5v7l6-3.5-6-3.5z" />
                                            </svg>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            {{-- video handled above inside the explanation block --}}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-center space-x-4">
                    <a href="{{ route('practice.course', $results['course']->id) }}" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                        Volver a {{ $results['course']->name }}
                    </a>
                    <a href="{{ route('practice.start', ['course' => $results['course']->id, 'difficulty' => 'Todos', 'question_count' => $results['totalQuestions']]) }}" class="btn btn-primary px-6 py-3">
                        Practicar de nuevo
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.ai-chat')

    <!-- Draggable video window -->
    <div id="videoWindow" class="fixed hidden z-50 bg-white rounded-lg shadow-lg" style="width:640px; height:360px; top:80px; left:80px;">
        <div id="videoHeader" class="cursor-move bg-gray-100 px-4 py-2 rounded-t-lg flex items-center justify-between">
            <div class="font-medium">Video</div>
            <div class="flex items-center space-x-2">
                <button id="videoClose" class="text-gray-600 hover:text-gray-800">✕</button>
            </div>
        </div>
        <div id="videoContent" class="p-2 w-full h-full flex items-center justify-center bg-black rounded-b-lg">
            <!-- video injected here -->
        </div>
    </div>
    <script src="/js/videoWindow.js"></script>
</x-app-layout>