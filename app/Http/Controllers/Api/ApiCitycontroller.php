<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class ApiCitycontroller extends Controller
{

    public function getAllCities()
    {
        $cities = City::all();
        return response()->json(['cities' => $cities], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
