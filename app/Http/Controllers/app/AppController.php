<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'executor')
            ->whereNotNull('photos')
            ->whereNotNull('name')
            ->whereNotNull('cost_up')
            ->inRandomOrder()
            ->paginate(10);
        return view('app.index', ['users' => $users]);
    }
    public function category($category)
    {
        $categoryId = (string) Category::where('alias', $category)->first()->id;
        $users = User::whereJsonContains('categories_id', $categoryId)->get();
        return view('app.category', ['users' => $users]);
    }
}
