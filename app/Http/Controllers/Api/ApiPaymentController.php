<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Helpers\Hmac;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiPaymentController extends Controller
{
    public function generatePaymentLink(Request $request)
    {
        // Your Prodamus form URL
        $linkToForm = 'https://promobilograf.proeducation.kz/';
        // Secret key for Prodamus
        $secretKey = env('PRODAMUS_SECRET_KEY');

        try {
            // Попытка получить пользователя из токена
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }
        $order = strtoupper(uniqid());
        // Gather data for the payment link
        if ($user->created_at > Carbon::parse('2024-11-22')) {
            $data = [
                'order_id' => $order,
                'customer_phone' => preg_replace('/\D/', '', $user->tel),
                'products' => [
                    [
                        'name' => 'product_name',
                        'price' => '10000',
                        'quantity' => '1',
                        'paymentMethod' => 'ACkz ',
                        'paymentObject' => 3,
                    ],
                ],
                'do' => 'pay',
                'discount_value' => '5000',
                'payment_method' => 'ACkz',
                'npd_income_type' => 'FROM_INDIVIDUAL',
            ];
        } else {
            $data = [
                'order_id' => $order,
                'customer_phone' => preg_replace('/\D/', '', $user->tel),
                'products' => [
                    [
                        'name' => 'product_name',
                        'price' => '10000',
                        'quantity' => '1',
                        'paymentMethod' => 'ACkz ',
                        'paymentObject' => 3,
                    ],
                ],
                'do' => 'pay',
                'payment_method' => 'ACkz',
                'npd_income_type' => 'FROM_INDIVIDUAL',
            ];
        }
        try {
            // Generate the signature
            $data['signature'] = \App\Http\Services\Helpers::createHmac($data, $secretKey);

            // Build the full link
            $link = sprintf('%s?%s', $linkToForm, http_build_query($data));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        $userSubscribe = Subscription::where('user_id', $user->id)->first();
        if ($userSubscribe) {
            if ($userSubscribe->payment_status === 'pending') {
                Subscription::updateOrCreate(
                    ['user_id' => $user->id], // Условие для поиска
                    [
                        'payment_status' => 'pending',
                        'order_id' => $order // Данные для обновления или создания
                    ]
                );
            } else if ($userSubscribe->payment_status === 'paid' && $userSubscribe->updated_at->diffInDays(now()) < 30) {
                $daysLeft = floor(31 - $userSubscribe->updated_at->diffInDays(now()));
                return response()->json([
                    'payment_link' => $link,
                    'order' => $order,
                    'is_active' => true,
                    'days_left' => $daysLeft,
                ]);
            }
        } else {
            Subscription::updateOrCreate(
                ['user_id' => $user->id], // Условие для поиска
                [
                    'payment_status' => 'pending',
                    'order_id' => $order // Данные для обновления или создания
                ]
            );
        }

        return response()->json([
            'payment_link' => $link,
            'order' => $order
        ]);
    }
    public function handle(Request $request)
    {
        $secretKey = env('PRODAMUS_SECRET_KEY'); // Секретный ключ берём из .env
        $signature = $request->header('Sign');  // Получаем подпись из заголовков

        try {
            // Проверяем, есть ли заголовок подписи
            if (empty($signature)) {
                throw new Exception('Signature not found', 400);
            }

            // Проверяем корректность подписи
            if (! \App\Http\Services\Helpers::verify($request->all(), $secretKey, $signature)) {
                throw new Exception('Signature incorrect', 400);
            }
            if ($request->payment_status !== 'success') {
                throw new Exception('Payment status incorrect', 400);
            }

            // Логика для успешного запроса
            $user = User::whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(tel, ' ', ''), '(', ''), ')', ''), '-', ''), '+', '') = ?", [preg_replace('/\D/', '', $request->customer_phone)])->first();
            // $user = User::where('tel', '+79999999999')->first();
            if (! $user) {
                return response()->json(['error' => 'Пользователь не найден'], 400);
            }
            Subscription::updateOrCreate(
                ['user_id' => $user->id], // Условие для поиска
                [
                    'payment_status' => 'paid',
                    'payment_expiry' => now()->addDays(30),
                ]
            );
            return response('success', 200);
        } catch (Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], $e->getCode() ?: 400);
        }
    }
}
