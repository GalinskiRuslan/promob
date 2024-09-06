<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VideoController extends Controller
{
    public function index()
    {
        $corrent_city = session('city');
        $params = [
            'corrent_city' => $corrent_city,
        ];
        return view('app.video',$params);
    }
}
