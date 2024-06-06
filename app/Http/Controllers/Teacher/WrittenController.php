<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Step;
use App\Models\Written;
use Illuminate\Http\Request;

class WrittenController extends Controller
{
    public function store(Request $request, $lesson_id)
    {
        $step = new Step();
        $step->lesson_id = $lesson_id;
        $step->type = 'written';
        $step->save();

        $written = new Written();
        $written->step_id = $step->id;
        $written->text = $request->text;
        $written->points = $request->points;
        $written->save();
    }
}
