<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $existingFavorite = Favorite::where('user_id', Auth::user()->id)
            ->where('course_id', $request->course_id)
            ->first();

        if ($existingFavorite) {
            return response()->json(['message' => 'Курс уже добавлен в избранное'], 409);
        }

        $favorite = new Favorite();
        $favorite->user_id = Auth::user()->id;
        $favorite->course_id = $request->course_id;
        $favorite->save();

        return response()->json(['message' => 'Курс добавлен в избранное']);
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('course_id', $request->course_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Курс удален из избранного']);
        }

        return response()->json(['message' => 'Курс не найден в избранном'], 404);
    }
}
