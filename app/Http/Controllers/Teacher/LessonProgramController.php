<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonProgramController extends Controller
{
    public function index($lesson_id)
    {
        $lesson = Lesson::with(['module.course', 'steps.content', 'steps.matching', 'steps.quiz', 'steps.written'])->findOrFail($lesson_id);
        return Inertia::render('TeacherCourse/LessonProgram', [
            'lesson' => $lesson
        ]);
    }
}
