<?php

namespace App\Http\Controllers\search;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SearchController extends Controller
{
    public function search(Request $request, $city , $category = null)
    {

        $search = $request->search;
        $minValue = $request->min_value;
        $maxValue = $request->max_value;
        $languages = $request->lang;
        $corrent_city = City::where('alias' , $city)->first();
        $cityId = $corrent_city->id;

        if(!empty($category)) {
            $categories = Category::where('alias',$category)->first();
            $query = User::withCategoriesAndCity([$categories->id], $corrent_city->id);
        } else {
            $query = User::where('cities_id', $cityId);
        }

        if (!is_array($languages)) {
            $languages = explode(',', $languages);
        }


        if ($search !== null) {
            $query->where(function ($query) use ($search) {
                $query->where('surname', 'like', '%' . $search . '%')
                    ->orWhere('surname_2', 'like', '%' . $search . '%')
                    ->orWhere('nickname', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('details', 'like', '%' . $search . '%')
                    ->orWhere('about_yourself', 'like', '%' . $search . '%');
            });
        }


        if ($minValue && $maxValue) {
            $query->whereBetween('cost_up', [$minValue, $maxValue]);
        }

        if (!empty($languages)) {
            $query->where(function ($q) use ($languages) {
                foreach ($languages as $language) {
                    $q->orWhere('language', 'like', '%' . $language . '%');
                }
            });
        }

        $users = $query->get();
        $params = [
            'corrent_city' => $corrent_city,
            'city' => $city,
            'users' => $users,
            'category' => $category,
        ];
        return view('app.city' , $params);
    }
}
