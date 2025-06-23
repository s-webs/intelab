<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $courses = Course::query()->with('user')->get();

        return Inertia::render('Admin/Dashboard', compact('courses'));
    }

    public function courseShow($course_id)
    {
        $course = Course::query()
            ->with([
                // модули → уроки → шаги → их связи
                'modules.lessons.steps.content',
                'modules.lessons.steps.quiz',
                'modules.lessons.steps.matching',
                'modules.lessons.steps.written',
            ])
            ->findOrFail($course_id);

        return Inertia::render('Admin/courseShow', [
            'course' => $course,
        ]);
    }

}
