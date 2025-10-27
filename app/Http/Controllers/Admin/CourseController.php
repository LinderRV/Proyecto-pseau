<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use App\Models\Course;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with('careers')->get();
        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $careers = Career::with('university')->get();
        return view('admin.courses.create', compact('careers'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'careers' => 'required|array',
            'careers.*' => 'exists:careers,id',
        ]);

        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
        ]);

        $course->careers()->attach($request->careers);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso creado exitosamente.');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $careers = Career::with('university')->get();
        $courseCareerIds = $course->careers->pluck('id')->toArray();
        
        return view('admin.courses.edit', compact('course', 'careers', 'courseCareerIds'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'careers' => 'required|array',
            'careers.*' => 'exists:careers,id',
        ]);

        $course->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
        ]);

        $course->careers()->sync($request->careers);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso actualizado exitosamente.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        // Check if the course has questions
        if ($course->questions()->count() > 0) {
            return redirect()->route('admin.courses.index')
                ->with('error', 'No se puede eliminar el curso porque tiene preguntas asociadas.');
        }
        
        $course->careers()->detach();
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Curso eliminado exitosamente.');
    }

    /**
     * Display a listing of questions for the specified course.
     */
    public function questions(Course $course)
    {
        $questions = $course->questions()->paginate(20);
        return view('admin.questions.index', compact('course', 'questions'));
    }

    /**
     * Show the form for creating a new question for the course.
     */
    public function createQuestion(Course $course)
    {
        return view('admin.questions.create', compact('course'));
    }

    /**
     * Store a newly created question in storage.
     */
    public function storeQuestion(Request $request, Course $course)
    {
        $request->validate([
            'question_text' => 'required|string',
            'problem_statement' => 'nullable|string',
            'explanation' => 'nullable|string',
            'youtube_id' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'options' => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ], [
            'correct_option.required' => 'Debes marcar la opción correcta.',
            'options.*.is_correct.required' => 'Debes marcar la opción correcta.',
            'options.required' => 'Debes agregar al menos 2 opciones.',
            'options.*.text.required' => 'Cada opción debe tener texto.',
        ]);

        // Handle YouTube URL
        $videoUrl = trim($request->input('youtube_id', ''));

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('question-images', 'public');
        }

        $question = Question::create([
            'course_id' => $course->id,
            'question_text' => $request->question_text,
            'problem_statement' => $request->problem_statement,
            'explanation' => $request->explanation,
            'video_url' => $videoUrl,
            'image' => $imagePath,
            'is_problem_solving' => !empty($videoUrl),
        ]);

        $options = $request->input('options');
        $optionKeys = array_keys($options);
        $correctKey = (int) $request->input('correct_option');
        $correctIndex = array_search($correctKey, $optionKeys, true);
        if ($correctIndex === false) {
            return back()->withInput()->withErrors(['options' => 'Opción correcta inválida.']);
        }
        foreach (array_values($options) as $index => $optionData) {
            QuestionOption::create([
                'question_id' => $question->id,
                'option_text' => $optionData['text'],
                'is_correct' => ($index === $correctIndex),
            ]);
        }

        return redirect()->route('admin.courses.questions', $course)
            ->with('success', 'Pregunta creada exitosamente.');
    }

    /**
     * Show the form for editing the specified question.
     */
    public function editQuestion(Question $question)
    {
        $course = $question->course;
        return view('admin.questions.edit', compact('question', 'course'));
    }

    /**
     * Update the specified question in storage.
     */
    public function updateQuestion(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'problem_statement' => 'nullable|string',
            'explanation' => 'nullable|string',
            'youtube_id' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'options' => 'required|array|min:2',
            'options.*.id' => 'nullable|exists:question_options,id',
            'options.*.text' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ], [
            'correct_option.required' => 'Debes marcar la opción correcta.',
            'options.*.is_correct.required' => 'Debes marcar la opción correcta.',
            'options.required' => 'Debes agregar al menos 2 opciones.',
            'options.*.text.required' => 'Cada opción debe tener texto.',
        ]);

        // Handle YouTube URL
        $videoUrl = trim($request->input('youtube_id', ''));

        // Handle image upload
        $imagePath = $question->image;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($question->image) {
                Storage::disk('public')->delete($question->image);
            }
            $imagePath = $request->file('image')->store('question-images', 'public');
        }

        $question->update([
            'question_text' => $request->question_text,
            'problem_statement' => $request->problem_statement,
            'explanation' => $request->explanation,
            'video_url' => $videoUrl,
            'image' => $imagePath,
            'is_problem_solving' => !empty($videoUrl),
        ]);

        // Update existing options and create new ones
        $existingIds = [];
        
        $options = $request->input('options');
        $optionKeys = array_keys($options);
        $correctKey = (int) $request->input('correct_option');
        $correctIndex = array_search($correctKey, $optionKeys, true);
        if ($correctIndex === false) {
            return back()->withInput()->withErrors(['options' => 'Opción correcta inválida.']);
        }
        foreach (array_values($options) as $index => $optionData) {
            if (isset($optionData['id'])) {
                // Update existing option
                $option = QuestionOption::find($optionData['id']);
                if ($option && $option->question_id == $question->id) {
                    $option->update([
                        'option_text' => $optionData['text'],
                        'is_correct' => ($index === $correctIndex),
                    ]);
                    $existingIds[] = $option->id;
                }
            } else {
                // Create new option
                $option = QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optionData['text'],
                    'is_correct' => ($index === $correctIndex),
                ]);
                $existingIds[] = $option->id;
            }
        }
        
        // Delete options that were removed
        $question->options()->whereNotIn('id', $existingIds)->delete();

        return redirect()->route('admin.courses.questions', $question->course)
            ->with('success', 'Pregunta actualizada exitosamente.');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroyQuestion(Question $question)
    {
        $course = $question->course;
        
        // Delete image if exists
        if ($question->image) {
            Storage::disk('public')->delete($question->image);
        }

        // Delete options first
        $question->options()->delete();
        
        // Delete the question
        $question->delete();

        return redirect()->route('admin.courses.questions', $course)
            ->with('success', 'Pregunta eliminada exitosamente.');
    }
}
