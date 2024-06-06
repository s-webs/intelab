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

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);


// Авторизация Laravel JetStream
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix('teacher')->group(function () {
        Route::resource('course', \App\Http\Controllers\Teacher\CourseController::class)->names('teacherCourse'); // Список курсов преподавателя
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
    });
});

// Ckeditor
Route::group(['middleware' => [\App\Http\Middleware\CKFinderSetup::class]], function () {
    Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
        ->name('ckfinder_connector');

    Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
        ->name('ckfinder_browser');
});
