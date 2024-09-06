<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $corrent_city = session('city');
        $params = [
            'user' => $user,
            'corrent_city' => $corrent_city,
        ];
        return view('app.user_statistic', $params);
    }
}
