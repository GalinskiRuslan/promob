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

        /*  if (session('city')) {
            return redirect('/' . session('city')->alias);
        } */
        $users = User::all()->shuffle();
        return view('app.index', ['users' => $users]);
    }
    public function category($category)
    {
        $categoryId = (string) Category::where('alias', $category)->first()->id;
        $users = User::whereJsonContains('categories_id', $categoryId)->get();
        return view('app.category', ['users' => $users]);
    }
}
