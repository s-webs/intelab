<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all()->map(function ($category) {
            $category->loadCount(['courses' => function ($query) {
                $query->where('status', true)->where('hidden', false);
            }]);
            return $category;
        });

        $stock = Stock::query()
            ->where('is_active', '=', true)
            ->latest('created_at')
            ->first();
        return Inertia::render('Home/Home', [
            'categories' => $categories,
            'stock' => $stock
        ]);
    }
}
