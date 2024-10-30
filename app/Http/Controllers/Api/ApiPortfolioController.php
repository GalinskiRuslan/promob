<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiPortfolioController extends Controller
{
    public function savePortfolioItem(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'Файл не найден'], 400);
        }
        try {
            // Попытка получить пользователя из токена
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }
        $file = $request->file('file');
        $mimeType = $file->getMimeType();
        if ($this->isImage($mimeType)) {
            $uploadedFileUrl = $this->uploadToCloudinary($file, $user->email, 'image');
        } elseif ($this->isVideo($mimeType)) {
            $uploadedFileUrl = $this->uploadToCloudinary($file, $user->email, 'video');
        } else {
            return response()->json(['message' => 'Файл должен быть изображением или видео'], 400);
        }

        if ($uploadedFileUrl) {
            try {
                $this->saveToUserGallery($user->tel, $uploadedFileUrl);
                return response()->json(['message' => 'Файл успешно загружен'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }

        return response()->json(['message' => 'Ошибка при загрузке файла'], 500);
    }
    public function deletePortfolioItem(Request $request)
    {
        try {
            // Попытка получить пользователя из токена
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }
        $userGallery = $user->gallery ? json_decode($user->gallery, true) : [];
        if (in_array($request->fileName, $userGallery)) {
            try {
                $result = array_diff($userGallery, [$request->fileName]);
                parse_str(parse_url($request->fileName)['query'], $queryParams);
                Cloudinary::destroy($queryParams['public_id']);
                $user->update([
                    'gallery' => json_encode($result),
                ]);
                return response()->json(['message' => 'Файл успешно удален'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
            }
        }
        return response()->json(['message' => 'Файл не найден'], 400);
    }
    // Проверка, является ли файл изображением
    private function isImage($mimeType)
    {
        return Str::contains($mimeType, 'image');
    }

    // Проверка, является ли файл видео
    private function isVideo($mimeType)
    {
        return Str::contains($mimeType, 'video');
    }

    // Загрузка файла на Cloudinary
    private function uploadToCloudinary($file, $folder, $type)
    {
        $options = [
            'folder' => $folder . '/portfolio',
            'quality' => 80,
            'fetch_format' => 'auto',
        ];

        if ($type === 'image') {
            $options['format'] = 'webp';
            return Cloudinary::upload($file->getRealPath(), $options)->getSecurePath();
        }

        if ($type === 'video') {
            $options['resource_type'] = 'video';
            return Cloudinary::uploadVideo($file->getRealPath(), $options)->getSecurePath();
        }

        return null;
    }

    // Сохранение файла в галерею пользователя
    private function saveToUserGallery($user, $uploadedFileUrl)
    {
        $userGallery = $user->gallery ? json_decode($user->gallery, true) : [];
        $userGallery[] = $uploadedFileUrl;

        $user->gallery = json_encode($userGallery);
        $user->save();
    }
}
