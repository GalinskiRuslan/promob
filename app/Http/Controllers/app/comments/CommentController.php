<?php

namespace App\Http\Controllers\app\comments;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Request $request , $id)
    {
        $user = User::where('id', $id)->first();
        $categories = Category::all();
        $cities = City::all();
        $corrent_city = session('city');
        $params = [
            'cities' => $cities,
            'categories' => $categories,
            'corrent_city' => $corrent_city,
            'user' => $user,
        ];
        return view('app.comments', $params);
    }
    public function store(Request $request)
    {
        $request->validate([
            'result' => 'required|string|max:4000',
            'target_user_id' => 'required|exists:users,id',
        ]);
        Comment::create([
            'user_id' => Auth::id(),
            'target_user_id' => $request->target_user_id,
            'result' => $request->result,
        ]);

        return redirect()->back();
    }
}
