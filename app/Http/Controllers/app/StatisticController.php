<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $corrent_city = session('city');
        if (!$corrent_city) {
            $corrent_city = City::get()->first();
        }
        $params = [
            'user' => $user,
            'corrent_city' => $corrent_city,
        ];
        return view('app.user_statistic', $params);
    }
}
