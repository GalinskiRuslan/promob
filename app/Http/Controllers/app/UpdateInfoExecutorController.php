<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class UpdateInfoExecutorController extends Controller
{
    public function about_executor()
    {
        $categories = Category::all();
        $cities = City::all();
        $corrent_city = session('city');
        if (!$corrent_city) {
            $corrent_city = City::get()->first();
        }
        $params = [
            'cities' => $cities,
            'categories' => $categories,
            'corrent_city' => $corrent_city,
        ];
        return view('auth.about_executor', $params);
    }
    public function update(Request $request)
    {

        $user = User::find(Auth::id());
        $request->validate([
            'cost_up' => 'required|numeric|max:50000000',
        ]);

        $user->categories_id = $request->categories_id;
        $user->cost_from = $request->cost_from;
        $user->cost_up = $request->cost_up;
        $user->details = $request->details;
        $user->about_yourself = $request->about_yourself;
        $user->cities_id = $request->cities_id;

        $languages = [];
        if ($request->rus == 'on') {
            $languages[] = 'rus';
        }
        if ($request->kaz == 'on') {
            $languages[] = 'kaz';
        }
        if ($request->en == 'on') {
            $languages[] = 'en';
        }
        $user->language = $languages;
        $user->save();

        return redirect()->route('portfolio');
    }
}
