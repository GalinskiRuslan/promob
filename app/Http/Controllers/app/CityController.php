<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CityController extends Controller
{
    public function index(Request $request, $city)
    {
        $corrent_city = City::where('alias', $city)->get()->first();
        if ($corrent_city) {
            $users = $corrent_city->users()->paginate(20);
        } else {
            $users = null;
            $corrent_city = City::get()->first();
        }

        session()->put('city', $corrent_city);
        $params = [
            'corrent_city' => $corrent_city,
            'city' => $city,
            'users' => $users,
        ];
        return view('app.index', $params);
    }
    public function city_category(Request $request, $city, $category)
    {
        $corrent_city = City::where('alias', $city)->firstOrFail();

        $categories = Category::where('alias', $category)->firstOrFail();

        $users = User::withCategoriesAndCity([$categories->id], $corrent_city->id)->get();

        $params = [
            'corrent_city' => $corrent_city,
            'city' => $city,
            'users' => $users,
            'category' => $category,
        ];

        return view('app.city', $params);
    }
}
