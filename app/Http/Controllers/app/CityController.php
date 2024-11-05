<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request, $city)
    {
        $corrent_city = City::where('alias', $city)->get()->first();
        $categoryAlias = $request->query('category');
        $category = $categoryAlias ? Category::where('alias', $categoryAlias)->first() : null;
        if ($corrent_city) {
            if ($category) {
                $users = User::withCategoriesAndCity([$category->id], $corrent_city->id)->whereNotNull('photos')->paginate(20);
            } else {
                // Если категории нет, берем всех пользователей города
                $users = $corrent_city->users()->paginate(20);
            }
        } else {
            $users = User::where('role', 'executor')->paginate(20);
            $corrent_city = City::get()->first();
        }

        session()->put('city', $corrent_city);
        $params = [
            'corrent_city' => $corrent_city,
            'city' => $city,
            'users' => $users,
        ];
        return view('app.city', $params);
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
