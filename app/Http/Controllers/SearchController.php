<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function results(Request $request)
    {
        $query = $request->input('query'); // Получаем поисковый запрос

        // Выполняем поиск по курсам, категориям и авторам
        $results = Course::query()
            ->where('name', 'like', "%{$query}%") // Поиск по названию курса
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%"); // Поиск по имени автора
            })
            ->with(['category', 'user']) // Загружаем связанные данные
            ->paginate(10); // Пагинация результатов

        return Inertia::render('Search/Index', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
