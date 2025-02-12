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

        return redirect(route('teacherCourse.index'));
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

    public function statistic(string $course_id)
    {
        $course = Course::with(['users'])->findOrFail($course_id);

        // Общее количество студентов
        $totalStudents = $course->users->count();

        // Количество студентов, завершивших курс (completed_at не null)
        $completedStudents = $course->users->filter(function ($user) {
            return $user->pivot->completed_at !== null;
        })->count();

        // Средний прогресс студентов
        $averageProgress = $course->users->avg('pivot.progress');

        // Группировка прогресса по диапазонам
        $progressRanges = [
            '0-25%' => 0,
            '25-50%' => 0,
            '50-75%' => 0,
            '75-100%' => 0,
        ];

        foreach ($course->users as $user) {
            $progress = $user->pivot->progress;

            if ($progress <= 25) {
                $progressRanges['0-25%']++;
            } elseif ($progress <= 50) {
                $progressRanges['25-50%']++;
            } elseif ($progress <= 75) {
                $progressRanges['50-75%']++;
            } else {
                $progressRanges['75-100%']++;
            }
        }

        // Данные для графика
        $progressData = [
            'labels' => array_keys($progressRanges), // Диапазоны прогресса
            'datasets' => [
                [
                    'label' => 'Количество студентов',
                    'data' => array_values($progressRanges), // Количество студентов в каждом диапазоне
                    'borderColor' => 'rgba(75, 192, 192, 1)', // Цвет линии
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)', // Цвет заливки под линией
                    'fill' => true, // Заливка под линией
                    'tension' => 0.4, // Кривизна линии
                ],
            ],
        ];
        $students = $course->users()->paginate(20);

        return Inertia::render('TeacherCourse/Statistic', [
            'course' => $course,
            'totalStudents' => $totalStudents,
            'completedStudents' => $completedStudents,
            'averageProgress' => $averageProgress,
            'progressData' => $progressData,
            'students' => $students
        ]);
    }
}
