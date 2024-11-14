<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Helpers\Hmac;
use Illuminate\Routing\Controller;

class ApiPaymentController extends Controller
{
    public function generatePaymentLink(Request $request)
    {
        // Your Prodamus form URL
        $linkToForm = 'https://promobilograf.proeducation.kz/';

        // Secret key for Prodamus
        $secretKey = '368694a3cab0e23dbfde51eda84931e98a1de873d3f88bc2a1519c620e13681d';
        $order = strtoupper(uniqid());
        // Gather data for the payment link
        $data = [
            'order_id' => $order,
            'customer_phone' => '7071423666',
            'products' => [
                [
                    'name' => 'product_name',
                    'price' => '10000',
                    'quantity' => '1',
                    'tax' => [
                        'tax_type' => 0,
                        'tax_sum' => 0,
                    ],
                    'paymentMethod' => 3,
                    'paymentObject' => 3,
                ],
            ],
            'do' => 'pay',
            'urlReturn' => 'https://demo.payform.ru/demo-return',
            'urlSuccess' => 'https://demo.payform.ru/demo-success',
            'urlNotification' => 'https://demo.payform.ru/demo-notification',
            'payment_method' => 'KZ',
            'npd_income_type' => 'FROM_INDIVIDUAL',
        ];

        // Generate the signature
        $data['signature'] = \App\Http\Services\Helpers::createHmac($data, $secretKey);

        // Build the full link
        $link = sprintf('%s?%s', $linkToForm, http_build_query($data));

        return response()->json([
            'payment_link' => $link,
            'order' => $order
        ]);
    }
}
