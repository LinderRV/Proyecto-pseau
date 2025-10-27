<x-app-layout>
    <div class="py-8 px-4 md:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Resultados de tu Examen</h1>
                    <span class="px-4 py-2 rounded-lg {{ $results['score'] >= 60 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-semibold">
                        Puntaje: {{ round($results['score'], 1) }}%
                    </span>
                </div>

                <!-- Stats summary -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <div class="text-blue-700 text-lg font-semibold">{{ $results['total_questions'] }}</div>
                        <div class="text-blue-600 text-sm">Preguntas totales</div>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                        <div class="text-green-700 text-lg font-semibold">{{ $results['correct_answers'] }}</div>
                        <div class="text-green-600 text-sm">Respuestas correctas</div>
                    </div>

                    <div class="bg-red-50 p-4 rounded-lg border border-red-100">
                        <div class="text-red-700 text-lg font-semibold">{{ $results['incorrect_answers'] }}</div>
                        <div class="text-red-600 text-sm">Respuestas incorrectas</div>
                    </div>

                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                        <div class="text-yellow-700 text-lg font-semibold">{{ formatTime($results['time_taken']) }}</div>
                        <div class="text-yellow-600 text-sm">Tiempo utilizado</div>
                    </div>
                </div>

                <!-- Results graph -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Resumen de resultados</h2>
                    <div class="h-6 bg-gray-200 rounded-full overflow-hidden">
                        @if($results['total_questions'] > 0)
                        <div class="h-6 bg-green-500 rounded-l-full" style="width: {{ ($results['correct_answers'] / $results['total_questions']) * 100 }}%; float: left;"></div>
                        <div class="h-6 bg-red-500" style="width: {{ ($results['incorrect_answers'] / $results['total_questions']) * 100 }}%; float: left;"></div>
                        <div class="h-6 bg-gray-400" style="width: {{ ($results['unanswered'] / $results['total_questions']) * 100 }}%; float: left;"></div>
                        @endif
                    </div>
                    <div class="flex justify-between text-xs mt-2">
                        <div class="flex items-center">
                            <span class="h-3 w-3 bg-green-500 inline-block mr-1 rounded-full"></span>
                            <span>Correctas ({{ round(($results['correct_answers'] / $results['total_questions']) * 100) }}%)</span>
                        </div>
                        <div class="flex items-center">
                            <span class="h-3 w-3 bg-red-500 inline-block mr-1 rounded-full"></span>
                            <span>Incorrectas ({{ round(($results['incorrect_answers'] / $results['total_questions']) * 100) }}%)</span>
                        </div>
                        <div class="flex items-center">
                            <span class="h-3 w-3 bg-gray-400 inline-block mr-1 rounded-full"></span>
                            <span>Sin responder ({{ round(($results['unanswered'] / $results['total_questions']) * 100) }}%)</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2 md:mb-0">Revisión de respuestas</h2>
                    <div>
                        <span class="text-gray-600 text-sm">
                            <span class="inline-block h-3 w-3 bg-green-200 rounded-full mr-1"></span> Tu respuesta fue correcta
                        </span>
                        <span class="text-gray-600 text-sm ml-4">
                            <span class="inline-block h-3 w-3 bg-red-200 rounded-full mr-1"></span> Tu respuesta fue incorrecta
                        </span>
                    </div>
                </div>

                <!-- Question review -->
                <div class="space-y-6">
                    @foreach($results['questions'] as $index => $question)
                    <div class="p-4 rounded-lg {{ $question['is_correct'] ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-100' }}">
                        <div class="flex justify-between">
                            <h3 class="font-semibold {{ $question['is_correct'] ? 'text-green-800' : 'text-red-800' }}">Pregunta {{ $index + 1 }}</h3>
                            <span class="{{ $question['is_correct'] ? 'text-green-800' : 'text-red-800' }}">
                                {{ $question['is_correct'] ? 'Correcta' : 'Incorrecta' }}
                            </span>
                        </div>

                        <p class="my-3 text-gray-700">{{ $question['question_text'] }}</p>

                        @if(!empty($question['image']))
                            <div class="mt-3">
                                <div class="flex justify-center">
                                    <img src="{{ asset('storage/' . $question['image']) }}" alt="Imagen de la pregunta" class="w-28 h-auto rounded-md shadow-sm mx-auto">
                                </div>
                            </div>
                        @endif

                        @if($question['selected_option'])
                        <div class="mb-1 border-t border-gray-200 ">
                            <span class="text-sm text-gray-600">Tu respuesta:</span>
                            <span class="ml-2 {{ $question['is_correct'] ? 'text-green-700' : 'text-red-700' }} font-medium">
                                {{ $question['selected_option'] }}
                            </span>
                        </div>
                        @else
                        <div class="mb-1">
                            <span class="text-sm text-gray-600">Tu respuesta:</span>
                            <span class="ml-2 text-gray-500 italic">Sin responder</span>
                        </div>
                        @endif

                        @if(!$question['is_correct'])
                        <div class="mb-1">
                            <span class="text-sm text-gray-600">Respuesta correcta:</span>
                            <span class="ml-2 text-green-700 font-medium">{{ $question['correct_option'] }}</span>
                        </div>
                        @endif

                        <div class="mt-3 pt-3 border-t border-gray-200 text-sm text-gray-700 flex items-start">
                            <div class="w-1/2">
                                @if($question['explanation'])
                                <span class="font-medium">Explicación:</span>
                                <p class="mt-1">{{ $question['explanation'] }}</p>
                                @endif
                            </div>
                            <div class="w-1/2 flex justify-end items-start">
                                @php
                                // determine video url if available
                                $videoUrl = null;
                                if(!empty($question['video_url'])) {
                                $videoUrl = $question['video_url'];
                                } elseif(!empty($question['video_embed_url'])) {
                                // try to extract id from embed url and build watch url
                                preg_match('/embed\/([A-Za-z0-9_-]{11})/', $question['video_embed_url'], $m);
                                if(isset($m[1])) {
                                $videoUrl = 'https://www.youtube.com/watch?v=' . $m[1];
                                }
                                }
                                @endphp
                                @if($videoUrl)
                                <div class="flex items-center space-x-2">
                                    <button type="button" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center shadow-sm ml-4" aria-label="Ver video" onclick="openVideoWindow({{ json_encode($videoUrl) }}, {{ json_encode($question['id']) }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M3 5.25C3 4.007 4.007 3 5.25 3h13.5C19.993 3 21 4.007 21 5.25v13.5c0 1.243-1.007 2.25-2.25 2.25H5.25C4.007 21 3 19.993 3 18.75V5.25zM10 8.5v7l6-3.5-6-3.5z" />
                                        </svg>
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- @if($question['explanation'])
                        <div class="mt-3 pt-3 border-t border-gray-200 text-sm text-gray-700">
                            <span class="font-medium">Explicación:</span>
                            <p class="mt-1">{{ $question['explanation'] }}</p>
                        </div>
                        @endif -->
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-center mt-8 space-x-4">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                        Volver al Dashboard
                    </a>
                    <a href="{{ route('exams.index') }}" class="btn btn-primary px-6 py-3">
                        Nuevo Examen
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.ai-chat')

    <!-- Reuse the same draggable video window used in practice results -->
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