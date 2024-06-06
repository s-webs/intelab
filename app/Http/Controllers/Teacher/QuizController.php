<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Step;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function store(Request $request, $lesson_id)
    {
        $step = new Step();
        $step->lesson_id = $lesson_id;
        $step->type = 'quiz';
        $step->save();

        $quiz = new Quiz();
        $quiz->step_id = $step->id;
        $quiz->text = $request->text;
        $quiz->answers = json_encode($request->answers);
        $quiz->correctAnswerId = $request->correctAnswerId;
        $quiz->points = $request->points;
        $quiz->save();
    }
}
