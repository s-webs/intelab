<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = auth()->user();
        return Inertia::render('Profile/Show', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = User::query()->findOrFail(auth()->id());

        $user->fill($request->all());

        $user->save();

        return redirect()->route('profile.show');
    }
}
