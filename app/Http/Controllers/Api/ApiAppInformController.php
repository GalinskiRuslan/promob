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
            $category->users_count = User::whereRaw("JSON_CONTAINS(categories_id, ?)", ['["' . $category->id . '"]'])->where('photos', '!=', null)->where('cost_from', '!=', null)->where(function ($query) {
                $query->whereHas('subscription', function ($subQuery) {
                    $subQuery->where('payment_status', 'paid')
                        ->where('updated_at', '>=', now()->subDays(30));
                })
                    ->orWhere(function ($query) {
                        $query->whereRaw('DATEDIFF(NOW(), created_at) < 30');
                    });
            })->count();
            $category->users_count += User::whereJsonContains('categories_id', $category->id)->count();
        }
        $categoriesUpTo13 = $categories->filter(fn($category) => $category->id <= 13)->reverse();

        // Остальные категории с ID больше 13
        $categoriesAbove13 = $categories->filter(fn($category) => $category->id > 13);
        $sortedCategories = $categoriesUpTo13->merge($categoriesAbove13);
        return response()->json(['categories' => $sortedCategories], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public function getCategoriesWithCity(Request $request)
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            // Подсчет пользователей, у которых в categories_id есть текущая категория
            $category->users_count = User::whereRaw("JSON_CONTAINS(categories_id, ?)", ['["' . $category->id . '"]'])->where('cities_id', $request->city)->where('photos', '!=', null)->where('cost_from', '!=', null)->where(function ($query) {
                $query->whereHas('subscription', function ($subQuery) {
                    $subQuery->where('payment_status', 'paid')
                        ->where('updated_at', '>=', now()->subDays(30));
                })
                    ->orWhere(function ($query) {
                        $query->whereRaw('DATEDIFF(NOW(), created_at) < 30');
                    });
            })->count();
        }
        $categoriesUpTo13 = $categories->filter(fn($category) => $category->id <= 13)->reverse();

        // Остальные категории с ID больше 13
        $categoriesAbove13 = $categories->filter(fn($category) => $category->id > 13);
        $sortedCategories = $categoriesUpTo13->merge($categoriesAbove13);
        return response()->json(['categories' => $sortedCategories], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
