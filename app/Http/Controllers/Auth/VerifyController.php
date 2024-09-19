<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VerifyController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:4',
        ]);
        $corrent_city = session('city');
        if (!$corrent_city) {
            $corrent_city = City::get()->first();
        }
        $user = User::where('tel', $request->email)
            ->where('verification_code', $request->verification_code)
            ->first();

        if (!$user) {
            $user = User::where('tel', $request->tel)
                ->where('verification_code', $request->verification_code)
                ->first();
        }
        if ($user) {
            $newPassword = Str::random(10);
            $user->password = Hash::make($newPassword);
            $user->is_verified = true;
            $user->verification_code = null;
            $user->save();
            Auth::login($user);
            session()->put('city', $corrent_city);
            return response()->json(['success' => true, 'role' => $user->role]);
        }

        return response()->json(['success' => false, 'message' => 'Неверный код верификации.'], 400);
    }
}
