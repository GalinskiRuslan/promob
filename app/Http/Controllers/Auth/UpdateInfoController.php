<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Statistic;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UpdateInfoController extends Controller
{


    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'instagram' => 'nullable|string|regex:/^[a-zA-Z0-9\.\-\_]+$/',
            'whatsapp' => 'nullable|string|regex:/^[a-zA-Z0-9+]+$/',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::find(Auth::id());

        $data = [
            'name' => $request->name,
            'surname' => $request->surname,
            'tel' => $request->tel,
            'surname_2' => $request->surname_2,
            'nickname' => $request->nickname,
            'nickname_true' => $request->nickname_true ? true : false,
            'site' => $request->site,
            'instagram' => $request->instagram,
            'whatsapp' => $request->whatsapp,
            'email' => $request->email,
        ];

        $id_user = $user->id;

        Statistic::create([
            'user_id' => $id_user,
        ]);

        if ($request->filled('password')) {
            Log::info('Password is being updated');
            $data['password'] = Hash::make($request->password);
        } else {
            Log::info('Password is not being updated');
        }

        $user->fill($data);
        $user->save();

        session()->put('password', $request->password);

        if (auth()->user()->role === 'executor') {
            return redirect()->route('about_executor');
        } else {
            return redirect()->to('/');
        }
    }



    public function index()
    {
        $corrent_city = session('city');
        $params = [
            'corrent_city' => $corrent_city,
        ];
        return view('auth.info',$params);
    }
}
