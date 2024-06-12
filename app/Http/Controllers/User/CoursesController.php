<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CoursesController extends Controller
{
    public function index($category_id)
    {
        $category = Category::findOrFail($category_id);
        $courses = $category->courses()->where('hidden', false)->where('status', true)->get();
        return Inertia::render('Courses/Index', [
            'category' => $category,
            'courses' => $courses
        ]);
    }

    public function show(Request $request, $course_id)
    {
        $course = Course::with('modules.lessons', 'user')->findOrFail($course_id);
        $lessonsTotalCount = $course->modules->reduce(function ($carry, $module) {
            return $carry + $module->lessons->count();
        }, 0);

        return Inertia::render('Courses/Show', [
            'course' => $course,
            'lessonsTotalCount' => $lessonsTotalCount,
        ]);
    }
}
