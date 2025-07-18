<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/catalog', [\App\Http\Controllers\HomeController::class, 'catalog'])->name('catalog.index');
Route::get('/category-{category_id}/courses', [\App\Http\Controllers\User\CoursesController::class, 'index'])->name('categoryCourses');
Route::get('/courses/course-{course_id}/show', [\App\Http\Controllers\User\CoursesController::class, 'show'])->name('showCourse');
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'results'])->name('search.results');

// Авторизация Laravel JetStream
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/getFavorites', [\App\Http\Controllers\User\FavoritesController::class, 'getFavorites'])->name('getFavorites');
    Route::post('/removeFromFavorite', [\App\Http\Controllers\User\FavoritesController::class, 'destroy'])->name('removeFromFavorite');
    Route::post('/addToFavorite', [\App\Http\Controllers\User\FavoritesController::class, 'store'])->name('addToFavorite');
    Route::get('/favorites', [\App\Http\Controllers\User\FavoritesController::class, 'index'])->name('favorites');

    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('courses', [\App\Http\Controllers\Admin\DashboardController::class, 'courses'])->name('admin.courses');
        Route::get('users', [\App\Http\Controllers\Admin\DashboardController::class, 'users'])->name('admin.users');
        Route::delete('/admin/users/{user}', [\App\Http\Controllers\Admin\DashboardController::class, 'deleteUser'])->name('admin.users.destroy');
        Route::get('dashboard/{course_id}', [\App\Http\Controllers\Admin\DashboardController::class, 'courseShow'])->name('admin.courseShow');
        Route::delete('/admin/courses/{id}', [\App\Http\Controllers\Admin\DashboardController::class, 'courseDelete'])->name('admin.course.destroy');
    });

    Route::prefix('teacher')->group(function () {
        Route::resource('course', \App\Http\Controllers\Teacher\CourseController::class)->names('teacherCourse'); // Список курсов преподавателя
        Route::get('course/{id}/statistic', [\App\Http\Controllers\Teacher\CourseController::class, 'statistic'])->name('teacherCourse.statistic'); // Статистика курса для преподавателя
        Route::get('course/{course_id}/program', [\App\Http\Controllers\Teacher\CourseProgramController::class, 'index'])->name('teacherCourse.program'); // Программа курса
        // Модули
        Route::post('course/{course_id}/program', [\App\Http\Controllers\Teacher\CourseProgramController::class, 'storeModule'])->name('teacherCourse.module.store');
        Route::put('course/module-{module_id}/update', [\App\Http\Controllers\Teacher\CourseProgramController::class, 'updateModule'])->name('teacherCourse.module.update');
        Route::delete('course/module-{module_id}/delete', [\App\Http\Controllers\Teacher\CourseProgramController::class, 'destroyModule'])->name('teacherCourse.module.destroy');
        // Уроки
        Route::post('course/add-lesson', [\App\Http\Controllers\Teacher\CourseProgramController::class, 'storeLesson'])->name('teacherCourse.lesson.store');
        Route::put('course/lesson-{lesson_id}/update', [\App\Http\Controllers\Teacher\CourseProgramController::class, 'updateLesson'])->name('teacherCourse.lesson.update');
        Route::delete('course/lesson-{lesson_id}/delete', [\App\Http\Controllers\Teacher\CourseProgramController::class, 'destroyLesson'])->name('teacherCourse.lesson.delete');
        // Программа урока
        Route::get('lesson-{lesson_id}/program', [\App\Http\Controllers\Teacher\LessonProgramController::class, 'index'])->name('teacherCourse.lesson.program');
        Route::post('course/lesson-{lesson_id}/add-step', [\App\Http\Controllers\Teacher\LessonProgramController::class, 'addStep'])->name('teacherCourse.lesson.addStep');
        Route::put('edit-step-{step_id}', [\App\Http\Controllers\Teacher\LessonProgramController::class, 'updateStep'])->name('teacherCourse.lesson.updateStep');
        Route::delete('step-{step_id}/delete', [\App\Http\Controllers\Teacher\LessonProgramController::class, 'deleteStep'])->name('teacherCourse.lesson.deleteStep');
        // Тесты
        Route::post('course/lesson-{lesson_id}/add-matching', [\App\Http\Controllers\Teacher\MatchingTestController::class, 'store'])->name('teacherCourse.lesson.addTest');
        Route::post('course/lesson-{lesson_id}/add-quiz', [\App\Http\Controllers\Teacher\QuizController::class, 'store'])->name('teacherCourse.lesson.addQuiz');
        Route::post('course/lesson-{lesson_id}/add-written', [\App\Http\Controllers\Teacher\WrittenController::class, 'store'])->name('teacherCourse.lesson.addWritten');
    });

    Route::prefix('student')->group(function () {
        Route::post('course-{course_id}/enroll', [\App\Http\Controllers\User\CoursesController::class, 'enroll'])->name('enrollStudentCourse');
        Route::get('course-{course_id}/learning', [\App\Http\Controllers\User\CoursesController::class, 'learning'])->name('learningStudentCourse');
        Route::get('course-{course_id}/module-{module_id}/lessons', [\App\Http\Controllers\User\CoursesController::class, 'courseLessons'])->name('learningStudentLessons');
        Route::get('course-{course_id}/lesson-{id}', [\App\Http\Controllers\Teacher\CourseController::class, 'lesson'])->name('lessonStudentCourse');
        Route::get('course-{course_id}/step-{step_id}', [\App\Http\Controllers\User\CoursesController::class, 'step'])->name('stepStudentCourse');
        Route::post('/steps/{step}/complete', [\App\Http\Controllers\User\ProgressController::class, 'updateStepProgress'])->name('steps.complete');
        Route::post('/steps/{step}/quiz/complete', [\App\Http\Controllers\User\ProgressController::class, 'updateQuizProgress'])->name('quiz.complete');
        Route::post('/modules/{module}/complete', [\App\Http\Controllers\User\ProgressController::class, 'updateModuleProgress'])->name('modules.complete');
        Route::post('/lessons/{lesson}/complete', [\App\Http\Controllers\User\ProgressController::class, 'updateLessonProgress'])->name('lessons.complete');
        Route::post('/steps/{step}/written/complete', [\App\Http\Controllers\User\ProgressController::class, 'updateWrittenProgress'])->name('written.complete');
        Route::post('/course-{course_id}/complete', [\App\Http\Controllers\User\ProgressController::class, 'courseComplete'])->name('courseComplete');
        Route::post('/update-progress', [\App\Http\Controllers\User\ProgressController::class, 'updateProgress'])->name('updateProgress');
        Route::get('/courses', [\App\Http\Controllers\User\CoursesController::class, 'studentCourses'])->name('userCourses');
        Route::post('/add-point', [\App\Http\Controllers\User\CoursesController::class, 'addPoint'])->name('addPoint');
    });
});

// Ckeditor
Route::group(['middleware' => [\App\Http\Middleware\CKFinderSetup::class]], function () {
    Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
        ->name('ckfinder_connector');

    Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
        ->name('ckfinder_browser');
});
