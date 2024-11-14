<?php

namespace App\Http\Services;

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
}
