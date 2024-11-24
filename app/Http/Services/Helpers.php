<?php

namespace App\Http\Services;

use App\Models\Subscription;
use Exception;
use Illuminate\Support\Carbon;

class Helpers
{
    public function getPublicIdFromUrl($url)
    {
        // Парсим URL и ищем public_id
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path']; // Получаем путь из URL

        // Убираем "/upload/" и расширение файла
        $publicIdWithExtension = substr($path, strpos($path, 'upload/') + strlen('upload/'));

        // Убираем расширение файла
        $publicId = pathinfo($publicIdWithExtension, PATHINFO_FILENAME);

        return $publicId;
    }
    static function createHmac($data, $key, $algo = 'sha256')
    {
        if (!in_array($algo, hash_algos()))
            return false;
        $data = (array) $data;
        array_walk_recursive($data, function (&$v) {
            $v = strval($v);
        });
        self::_sort($data);
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $data = preg_replace_callback('/((\\\u[01-9a-fA-F]{4})+)/', function ($matches) {
                return json_decode('"' . $matches[1] . '"');
            }, json_encode($data));
        } else {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        return hash_hmac($algo, $data, $key);
    }
    static function verify($data, $key, $sign, $algo = 'sha256')
    {
        $_sign = self::createHmac($data, $key, $algo);
        return ($_sign && (strtolower($_sign) == strtolower($sign)));
    }

    static private function _sort(&$data)
    {
        ksort($data, SORT_REGULAR);
        foreach ($data as &$arr)
            is_array($arr) && self::_sort($arr);
    }
    static function isActiveUser($user)
    {
        $payment = Subscription::where('user_id', $user->id)->first();

        if ($payment) {
            if ($payment->payment_status == 'paid' && $payment->updated_at->diffInDays(now()) < 30) {
                $daysLeft = floor(31 - $payment->updated_at->diffInDays(now()));
                return ['is_active' => true, 'days_left' => $daysLeft];
            } else {
                return false;
            }
        } else if ($user->created_at->diffInDays(now()) < 30) {
            $daysLeft = floor(30 - $user->created_at->diffInDays(now()));
            return ['is_active' => true, 'days_left' => $daysLeft];
        } else {
            return false;
        }
    }
    static function isUserRegisteredRelativeToDate($user, $date, $comparison = 'after')
    {
        // Преобразуем дату в Carbon
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);

        // Проверяем дату создания пользователя
        if ($comparison === 'after') {
            return $user->created_at->gt($date); // позже указанной даты
        } elseif ($comparison === 'before') {
            return $user->created_at->lt($date); // раньше указанной даты
        }

        // Если тип сравнения некорректный, выбрасываем исключение
        throw new Exception("Invalid comparison type. Use 'after' or 'before'.");
    }
}
