<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $courses = Course::query()->with('user')->get();

        return Inertia::render('Admin/Dashboard', compact('courses'));
    }

    public function courses()
    {
        $courses = Course::query()->with('user')->get();

        return Inertia::render('Admin/Courses', compact('courses'));
    }

    public function users(Request $request)
    {
        $excludedEmails = [
            'admin@example.com',
            'test@example.com',
        ];

        $query = User::whereNotIn('email', $excludedEmails);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return Inertia::render('Admin/Users', [
            'users' => $users,
            'filters' => [
                'search' => $search
            ]
        ]);
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

    public function deleteUser(User $user)
    {
        $user->delete();
    }

}
