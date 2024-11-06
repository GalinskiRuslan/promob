<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class ApiAppInformController extends Controller
{

    public function getAllCategories()
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            // Подсчет пользователей, у которых в categories_id есть текущая категория
            $category->users_count = User::whereRaw("JSON_CONTAINS(categories_id, ?)", ['["' . $category->id . '"]'])->count();
        }
        return response()->json(['categories' => $categories], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
