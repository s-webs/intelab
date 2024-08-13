<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    public function getFavorites()
    {
        $user = Auth::user();
        // Получаем избранные элементы пользователя
        $favorites = $user->favorites;

        // Возвращаем данные в формате JSON
        return response()->json([
            'favorites' => $favorites,
        ]);
    }

    public function store(Request $request)
    {
        $favorite = new Favorite();
        $favorite->user_id = Auth::user()->id;
        $favorite->course_id = $request->course_id;
        $favorite->save();

        return response()->json(['message' => 'Курс добавлен в избранное']);
    }


    public function destroy(Request $request)
    {
        $user = Auth::user();

        // Удаляем курс из избранного пользователя
        $user->favorites()->where('course_id', $request->course_id)->delete();

        return response()->json(['message' => 'Курс удален из избранного']);
    }
}
