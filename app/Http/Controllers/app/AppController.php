<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {

        if (session('city')) {
            return redirect('/' . session('city')->city);
        }
        return redirect('/almaty');
    }
}
