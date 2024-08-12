<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Module;
use App\Models\ModuleUser;
use App\Models\Step;
use App\Models\StepUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CoursesController extends Controller
{
    public function index($category_id)
    {
        // Получаем родительскую категорию
        $category = Category::findOrFail($category_id);

        // Получаем все дочерние категории, у которых есть курсы
        $childCategories = Category::where('parent_id', $category->id)
            ->whereHas('courses', function ($query) {
                $query->where('hidden', false)->where('status', true);
            })
            ->with(['courses' => function ($query) {
                $query->where('hidden', false)->where('status', true);
            }])
            ->get();

        // Извлекаем все курсы из родительской и дочерних категорий
        $courses = Course::where('category_id', $category->id)
            ->with('user')
            ->orWhereIn('category_id', $childCategories->pluck('id'))
            ->where('hidden', false)
            ->where('status', true)
            ->get();

        return Inertia::render('Courses/Index', [
            'category' => $category,
            'courses' => $courses,
            'childCategories' => $childCategories
        ]);
    }


    public function show(Request $request, $course_id)
    {
        $course = Course::with('modules.lessons', 'user', 'users')->findOrFail($course_id);
        $lessonsTotalCount = $course->modules->reduce(function ($carry, $module) {
            $carry = (int)$carry;
            $lessonsTotal = (int)$module->lessons->count();
            return $carry + $lessonsTotal;
        }, 0);

        if (Auth::check()) {
            $isUserEnrolled = $request->user()->courseUsers()->where('course_id', $course_id)->exists();
        } else {
            $isUserEnrolled = null;
        }

        return Inertia::render('Courses/Show', [
            'course' => $course,
            'lessonsTotalCount' => $lessonsTotalCount,
            'isUserEnrolled' => $isUserEnrolled,
        ]);
    }

    public function enroll(Request $request, $course_id)
    {
        $course = Course::findOrFail($course_id);
        $user = auth()->user();

        $enrollment = $user->courseUsers()->where('course_id', $course_id)->first();

        if ($enrollment) {
            return to_route('learningStudentCourse', $course_id);
        } else {
            if ($course->price > 0) {
                // ... логика для обработки платного курса ...
            } else {
                $user->courseUsers()->create([
                    'course_id' => $course_id,
                    'paid' => true,
                    'progress' => 0,
                    'started_at' => now(),
                ]);
            }
        }
        return to_route('learningStudentCourse', $course_id);
    }

    public function learning($course_id)
    {
        $user = auth()->user();
        $courseUser = $user->courseUsers()->where('course_id', $course_id)->first();
        $course = Course::with(['modules.lessons', 'modules' => function ($query) {
            $query->with(['moduleUsers' => function ($query) {
                $query->where('user_id', auth()->user()->id);
            }]);
        }, 'modules.userProgress' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->findOrFail($course_id);

        $previousModuleCompleted = true;
        $course->modules->each(function ($module, $key) use ($user, &$previousModuleCompleted) {
            $module->is_accessible = $previousModuleCompleted;
            if ($previousModuleCompleted && $module->userProgress && !$module->userProgress->completed) {
                $previousModuleCompleted = false;
            }
        });

        return Inertia::render('UserCourse/Training', [
            'course' => $course,
            'courseUser' => $courseUser
        ]);
    }

    public function courseLessons($course_id, $module_id)
    {
        $module = Module::with([
            'course',
            'moduleUsers' => function ($query) {
                $query->where('user_id', auth()->user()->id);
            },
            'lessons' => function ($query) {
                $query->with(['lessonUsers' => function ($query) {
                    $query->where('user_id', auth()->user()->id);
                }]);
            }
        ])->findOrFail($module_id);

        $user = auth()->user();
        $courseUser = $user->courseUsers()->where('course_id', $course_id)->first();
        ModuleUser::query()->firstOrCreate(
            [
                'module_id' => $module_id,
                'user_id' => $user->id,
            ],
            [
                'started_at' => now(),
            ]
        );

        return Inertia::render('UserCourse/Lessons', [
            'module' => $module,
            'courseUser' => $courseUser
        ]);
    }

    public function step($course_id, $step_id)
    {
        $user = auth()->user();
        $courseUser = $user->courseUsers()->where('course_id', $course_id)->first();

        $step = Step::with([
            'lesson.module.course',
            'content',
            'matching',
            'quiz',
            'written',
            'stepUsers' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }
        ])->findOrFail($step_id);

        $stepUser = StepUser::query()->where('step_id', $step->id)
            ->where('user_id', $user->id)
            ->first();

        $steps = Step::where('lesson_id', $step->lesson->id)
            ->orderBy('order')
            ->get()
            ->each(function ($s) use ($user) {
                $s->completed = $s->stepUsers->where('user_id', $user->id)->isNotEmpty();
                $s->answers = $s->completed ? $s->stepUsers->where('user_id', $user->id)->first()->answers : null;
                $s->setRelation('stepUsers', $s->stepUsers->where('user_id', $user->id)); // Сохраняем отношение с данными только текущего пользователя
            });

        $currentStepIndex = $steps->search(function ($s) use ($step_id) {
            return $s->id == $step_id;
        });

        $steps->transform(function ($s) {
            $s->completed = $s->stepUsers->isNotEmpty();
            $s->answers = $s->completed ? $s->stepUsers->first()->answers : null;
            unset($s->stepUsers); // Удаление лишней информации
            return $s;
        });

        return Inertia::render('UserCourse/Step', [
            'step' => $step,
            'steps' => $steps,
            'stepUser' => $stepUser,
            'currentStepIndex' => $currentStepIndex,
            'courseUser' => $courseUser
        ]);
    }

    public function studentCourses()
    {
        $user = auth()->user();
        $courses = $user->courseUsers()->with(['course', 'userProgress'])->get();

        return Inertia::render('UserCourse/Index', [
            'courses' => $courses
        ]);
    }

    public function addPoint(Request $request)
    {
        $user = auth()->user();
        $courseUser = $user->courseUsers->where('course_id', $request->course_id)->first();
        $points = $courseUser->points;
        $points += $request->points;
        $courseUser->points = $points;
        $courseUser->save();
    }
}
