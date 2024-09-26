<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SmsController;
use App\Http\Services\SmsService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            $smsService = new SmsController();
            try {
                $response = $smsService->sendSMS($request->tel, $verificationCode);
                if ($response->getData()->error) {
                    return response()->json(['message' => $response->getData()->error], 400);
                }
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
            return response()->json(['message' => 'Код отправлен', 'code_id' => $code_id], 200);
        } else {
            return response()->json(['message' => 'Пользователь уже зарегистрирован'], 400);
        }
    }
}
