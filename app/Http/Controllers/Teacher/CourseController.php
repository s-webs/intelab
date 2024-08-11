<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonUser;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('TeacherCourse/Index', [
            'courses' => Course::query()->where('user_id', auth()->user()->id)->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return Inertia::render('TeacherCourse/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $course = new Course();
        $course->category_id = $request->category;
        $course->image = $request->image;
        $course->language = $request->language;
        $course->name = $request->name;
        $course->description = $request->description;
        $course->content = $request->html;
        $course->duration = $request->duration;
        $course->type = $request->type;
        $course->hidden = $request->hidden;
        $course->price = $request->price;
        $course->old_price = $request->old_price;
        $course->user_id = auth()->user()->id;
        $course->save();

        return redirect(route('teacherCourse.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::all();
        $course = Course::with('category')->findOrFail($id);
        return Inertia::render('TeacherCourse/Edit', [
            'course' => $course,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);
        $course->category_id = $request->category;
        $course->image = $request->image;
        $course->language = $request->language;
        $course->name = $request->name;
        $course->price = $request->price;
        $course->old_price = $request->old_price;
        $course->description = $request->description;
        $course->content = $request->html;
        $course->duration = $request->duration;
        $course->hidden = $request->hidden;
        $course->type = $request->type;
        $course->status = $request->status;
        $course->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
    }

    public function lesson($course_id, $lesson_id)
    {
        $user = auth()->user();
        $courseUser = $user->courseUsers()->where('course_id', $course_id)->first();

        LessonUser::query()->firstOrCreate(
            [
                'lesson_id' => $lesson_id,
                'user_id' => $user->id,
            ],
            [
                'started_at' => now(),
            ]
        );

        $lesson = Lesson::with('module.course', 'steps.content', 'steps.matching', 'steps.quiz', 'steps.written')->findOrFail($lesson_id);
        return Inertia::render('UserCourse/Lesson', [
            'lesson' => $lesson,
            'courseUser' => $courseUser
        ]);
    }
}
