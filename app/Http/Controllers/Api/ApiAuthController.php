<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterWithMailRequest;
use App\Http\Services\SmsService;
use App\Models\User;
use App\Models\VerifySms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiAuthController extends Controller
{
    public function getSmsCode(Request $request)
    {
        try {
            $request->validate([
                'tel' => 'required|string|min:10|max:11',

            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
        if (!User::where('tel', $request->tel)->first() || !User::where('tel', $request->tel)->first()->is_verified) {
            $verificationCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $code_id = Str::uuid();
            // $smsService = new SmsController();
            try {
                // $response = $smsService->sendSMS($request->tel, $verificationCode);
                // if ($response->getData()->error) {
                // return response()->json(['message' => $response->getData()->error], 400);
                // } else {
                VerifySms::updateOrCreate(
                    ['tel' => $request->tel],
                    [
                        'code_id' => $code_id,

                        'code' => $verificationCode
                    ]
                );
                return response()->json(['message' => 'Код отправлен', 'code_id' => $code_id], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                // }
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        } else {
            return response()->json(['message' => 'Пользователь уже зарегистрирован'], 400);
        }
    }
    public function registrationWithSms(Request $request)
    {
        try {
            $request->validate([
                'code_id' => 'required|string',
                'code' => 'required|string|size:4',
                'password' => 'required|string'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
        $verifySms = VerifySms::where('code_id', $request->code_id)->first();
        if (!$verifySms) {
            return response()->json(['message' => 'Код не найден'], 400);
        }
        if ($verifySms->code != $request->code) {
            return response()->json(['message' => 'Неверный код'], 400);
        } else {
            $user = User::create([
                'tel' => $verifySms->tel,
                'role' => 'executor',
                'password' => bcrypt($request->password),
                'is_verified' => 1
            ]);
            VerifySms::where('code_id', $request->code_id)->delete();
            return response()->json(['message' => 'Регистрация прошла успешно', 'token' => JWTAuth::fromUser($user)], 200);
        }
    }
    public function setNewPassword(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:8',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
        try {
            // Попытка получить пользователя из токена
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }

        $user->update([
            'password' => bcrypt($request->password),
        ]);
        return response()->json(['message' => 'Пароль успешно изменен'], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'tel' => 'required|string|min:10|max:11',
                'password' => 'required|string|min:8',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        $user = User::where('tel', $request->tel)->first();
        if (!$user) {
            return response()->json(['message' => 'Пользователь не найден'], 400);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Неверный пароль'], 400);
        }
        $token = JWTAuth::fromUser($user);
        return response()->json(['token' => $token], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Вы вышли из аккаунта'], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public function registerWithEmail(RegisterWithMailRequest $request)
    {
        $validated = $request->validated();
        try {
            $user = User::create([
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => '',
            ]);

            return response()->json(['message' => 'Регистрация прошла успешно', 'token' => JWTAuth::fromUser($user)], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
