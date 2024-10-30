<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ApiAppInformController extends Controller
{

    public function getAllCategories()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
