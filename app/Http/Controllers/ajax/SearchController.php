<?php

namespace App\Http\Controllers\ajax;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Str::ucfirst($request->search);

        $results = City::where('city', 'LIKE', "%{$query}%")->get();

        return response()->json(['results' => $results]);
    }
}
