<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\ModuleUser;
use App\Models\Step;
use App\Models\StepUser;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function updateModuleProgress($moduleId)
    {
        $progress = ModuleUser::query()->updateOrCreate([
            'user_id' => Auth::id(),
            'module_id' => $moduleId,
        ], [
            'completed' => true,
            'completed_at' => now(),
        ]);
    }

    public function updateLessonProgress($lessonId)
    {
        $progress = LessonUser::query()->updateOrCreate(
            [
                'user_id' => Auth::id(),
                'lesson_id' => $lessonId,
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );

        $lesson = Lesson::query()->findOrFail($lessonId);

        return redirect()->route('learningStudentLessons', [$lesson->module->course->id, $lesson->module->id]);

    }

    public function updateProgress(Request $request)
    {
        UserProgress::query()->updateOrCreate(
            [
                'course_id' => $request->course_id,
                'course_user_id' => $request->course_user_id,
            ],
            [
                'type' => $request->type,
                'target_id' => $request->target_id,
                'course_complete' => $request->course_complete
            ]
        );
    }

    public function updateStepProgress(Request $request, $stepId)
    {
        $user = $request->user();
        $step = Step::findOrFail($stepId);

        if ($step->type === 'content') {
            $progress = StepUser::query()->firstOrCreate([
                'user_id' => $user->id,
                'step_id' => $stepId,
            ], [
                'completed' => true,
                'completed_at' => now(),
            ]);
        } else {
            $progress = StepUser::query()->firstOrCreate([
                'user_id' => $user->id,
                'step_id' => $stepId,
            ], [
                'completed' => true,
                'completed_at' => now(),
                'answers' => $request->input('answers')
            ]);
        }
    }

    public function updateQuizProgress(Request $request, $stepId)
    {

        $user = $request->user();
        $step = Step::findOrFail($stepId);

        $progress = StepUser::query()->firstOrCreate([
            'user_id' => $user->id,
            'step_id' => $stepId,
        ], [
            'completed' => true,
            'completed_at' => now(),
            'answers' => $request->answers
        ]);
    }

    public function courseComplete(Request $request, $course_id)
    {
        $user = auth()->user();
        $courseUser = $user->courseUsers()->where('course_id', $course_id)->first();
        $courseUser->progress = 100;
        $courseUser->completed_at = now();
        $courseUser->save();
    }

    public function updateWrittenProgress(Request $request, $stepId)
    {
        $user = $request->user();
        $step = Step::findOrFail($stepId);

        $progress = StepUser::query()->firstOrCreate([
            'user_id' => $user->id,
            'step_id' => $stepId,
        ], [
            'completed' => true,
            'completed_at' => now(),
            'answers' => $request->answers
        ]);
    }
}
