<?php

namespace App\Http\Controllers\Api;

use App\Models\Subscription;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Helpers\Hmac;
use Illuminate\Routing\Controller;
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
        try {
            // Gather data for the payment link
            $data = [
                'order_id' => $order,
                'customer_phone' => preg_replace('/\D/', '', $user->tel),
                'products' => [
                    [
                        'name' => 'product_name',
                        'price' => '500',
                        'quantity' => '1',
                        'tax' => [
                            'tax_type' => 0,
                            'tax_sum' => 0,
                        ],
                        'paymentMethod' => 'ACkz',
                        'paymentObject' => 3,
                    ],
                ],
                'do' => 'pay',
                'urlReturn' => 'https://demo.payform.ru/demo-return',
                'urlSuccess' => 'https://demo.payform.ru/demo-success',
                'urlNotification' => 'https://demo.payform.ru/demo-notification',
                'payment_method' => 'ACkz',
                'npd_income_type' => 'FROM_INDIVIDUAL',
            ];

            // Generate the signature
            $data['signature'] = \App\Http\Services\Helpers::createHmac($data, $secretKey);

            // Build the full link
            $link = sprintf('%s?%s', $linkToForm, http_build_query($data));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        Subscription::updateOrCreate(
            ['user_id' => $user->id], // Условие для поиска
            [
                'payment_status' => 'pending',
                'order_id' => $order // Данные для обновления или создания
            ]
        );

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
            $user = User::whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(tel, ' ', ''), '(', ''), ')', ''), '-', '') = ?", [preg_replace('/\D/', '', $request->customer_phone)])->first();
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
