<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepContent;
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

    public function addStep(Request $request, $lesson_id)
    {
        $step = new Step();
        $step->lesson_id = $lesson_id;
        $step->type = $request->stepType;
        $step->save();

        if ($request->stepType === 'content') {
            $stepContent = new StepContent();
            $stepContent->step_id = $step->id;
            $stepContent->content = $request->content;
            $stepContent->save();
        }
    }

    public function updateStep(Request $request, $step_id)
    {
        $step = Step::findOrFail($step_id);
        $stepContent = $step->content;
        $stepContent->content = $request->content;
        $stepContent->save();
    }

    public function deleteStep($step_id)
    {
        $step = Step::query()->findOrFail($step_id);
        $step->delete();
    }
}
