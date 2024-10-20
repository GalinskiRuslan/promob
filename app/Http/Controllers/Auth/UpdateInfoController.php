<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Statistic;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class UpdateInfoController extends Controller
{


    public function update(Request $request)
    {
        $user = User::find(Auth::id());
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'instagram' => 'nullable|string|regex:/^[a-zA-Z0-9\.\-\_]+$/',
            'whatsapp' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);


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
        if (Auth::user()->role === 'executor') {
            return redirect()->route('about_executor');
        } else {
            return redirect()->to('/');
        }
    }



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
        return view('auth.info', $params);
    }
}
