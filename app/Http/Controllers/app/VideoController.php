<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VideoController extends Controller
{
    public function index()
    {
        $corrent_city = session('city');
        if ($corrent_city) {
            $params = [
                'corrent_city' => $corrent_city,
            ];
        } else {
            $params = ['corrent_city' =>  City::get()->first()];
        }
        return view('app.video', $params);
    }
}
