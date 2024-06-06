<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseProgramController extends Controller
{
    public function index($course_id)
    {
        $course = Course::with(['modules', 'modules.lessons'])->findOrFail($course_id);
        return Inertia::render('TeacherCourse/Program', [
            'course' => $course,
        ]);
    }

    public function storeModule(Request $request)
    {
        $course_id = $request->course_id;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'course_id' => 'required|integer'
        ]);
        Module::create($validated);

        return to_route('teacherCourse.program', $course_id);
    }

    public function updateModule($module_id, Request $request)
    {
        $module = Module::query()->findOrFail($module_id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);
        $module->update($validated);
        return to_route('teacherCourse.program', $module->course->id);
    }

    public function destroyModule($module_id)
    {
        $module = Module::query()->findOrFail($module_id);
        $courseId = $module->course->id;
        $module->delete();

        return to_route('teacherCourse.program', $courseId);
    }

    public function storeLesson(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'module_id' => 'required|integer'
        ]);
        Lesson::create($validated);
    }

    public function updateLesson(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);
        $lesson = Lesson::query()->findOrFail($request->id);
        $lesson->update($validated);
        return to_route('teacherCourse.program', $lesson->module->course->id);
    }

    public function destroyLesson($lesson_id)
    {
        $lesson = Lesson::query()->findOrFail($lesson_id);
        $courseId = $lesson->module->course->id;
        $lesson->delete();

        return to_route('teacherCourse.program', $courseId);
    }
}
