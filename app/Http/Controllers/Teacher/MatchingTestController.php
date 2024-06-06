<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\MatchingTest;
use App\Models\Step;
use Illuminate\Http\Request;

class MatchingTestController extends Controller
{
    public function store(Request $request, $lesson_id)
    {
        $data = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|numeric',
            'items.*.question' => 'required|string',
            'items.*.answer' => 'required|string',
            'description' => 'required'
        ]);
        $step = new Step();
        $step->lesson_id = $lesson_id;
        $step->type = 'matching';
        $step->save();

        MatchingTest::create([
            'step_id' => $step->id,
            'description' => $data['description'],
            'question' => json_encode(array_map(function ($item) {
                return ['id' => $item['id'] ?? null, 'question' => $item['question']];
            }, $data['items'])),
            'answers' => json_encode(array_map(function ($item) {
                return ['id' => $item['id'] ?? null, 'answer' => $item['answer']];
            }, $data['items'])),
        ]);
    }
}
