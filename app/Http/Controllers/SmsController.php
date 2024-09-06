<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;


class SmsController
{
    public function sendSMS($recipient , $verification_code)
    {
        $apiKey = config('services.mobizon.api_key');
        $apiDomain = config('services.mobizon.api_domain', 'api.mobizon.kz');
        $message = "Ваш код $verification_code";

        $client = new Client([
            'base_uri' => "https://$apiDomain",
        ]);

        try {
            $response = $client->post('/service/message/sendSMSMessage', [
                'form_params' => [
                    'recipient' => $recipient,
                    'text' => $message,
                    'from' => 'PROmobi',
                    'apiKey' => $apiKey,
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                return response()->json($data);
            } else {
                // Обработка ошибки
                return response()->json(['error' => 'Failed to send SMS'], $response->getStatusCode());
            }
        } catch (RequestException $e) {
            Log::error('Error sending SMS: ' . $e->getMessage());

            // Обрабатываем ошибку
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return response()->json(['error' => 'Failed to send SMS', 'details' => $response->getBody()->getContents()], $response->getStatusCode());
            } else {
                return response()->json(['error' => 'Failed to send SMS']);
            }
        }
    }
}
