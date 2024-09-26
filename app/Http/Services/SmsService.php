<?

namespace App\Http\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SmsService
{
    public function mobizonSendSms($phone, $verification_code)
    {
        $apiKey = config('services.mobizon.api_key');
        $apiDomain = config('services.mobizon.api_domain', 'api.mobizon.kz');
        $message = "Код для подтверждения регистрации на платформе profmo: $verification_code";

        $client = new Client([
            'base_uri' => "https://$apiDomain",
        ]);
        try {
            $response = $client->post('/service/message/sendSMSMessage', [
                'form_params' => [
                    'recipient' => $phone,
                    'text' => $message,
                    'from' => 'PROmobi',
                    'apiKey' => $apiKey,
                ],
            ]);

            if ($response->getStatusCode() == 200 && $response->getBody()->code === 0) {
                $data = json_decode($response->getBody()->getContents(), true);
                return response()->json($data);
            } else if ($response->getBody()->code === 1) {
                return response()->json(['error' => 'Возникла ошибка при отправке SMS'], $response->getStatusCode());
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return response()->json(['error' => 'Failed to send SMS', 'details' => $response->getBody()->getContents()], $response->getStatusCode());
            } else {
                return response()->json(['error' => 'Failed to send SMS']);
            }
        }
    }
}
