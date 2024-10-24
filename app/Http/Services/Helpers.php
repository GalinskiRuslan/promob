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
}
