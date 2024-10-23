<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use WebPConvert\WebPConvert;
use FFMpeg;

class PortofolioController extends Controller
{
    public function index()
    {
        $corrent_city = Auth::user()->city;

        $params = [
            'corrent_city' => $corrent_city,
        ];
        return view('auth.portfolio', $params);
    }
    public function savePortfolioItem(Request $request)
    {
        $user = Auth::user();
        if ($request->hasFile('file')) {
            if (Str::contains($request->file('file')->getMimeType(), 'image')) {
                // Локальное временное хранение файла для конвертации
                $tempPath = $request->file('file')->getPathname();
                $outputWebPPath = $tempPath . '.webp';

                // Конвертируем изображение в WebP
                WebPConvert::convert($tempPath, $outputWebPPath, [
                    'quality' => 85, // Устанавливаем качество WebP
                ]);

                // Сохраняем WebP версию в S3
                $webpPath = $user->email . '/portfolio/' . pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
                Storage::disk('s3')->put($webpPath, file_get_contents($outputWebPPath));

                // Получаем URL WebP изображения
                $webpUrl = "https://dspt7sohnkg6q.cloudfront.net/" . $webpPath;

                // Обновляем галерею пользователя, добавляя только WebP-версию
                $userGallery = $user->gallery ? json_decode($user->gallery, true) : [];
                $userGallery[] = $webpUrl;

                // Сохраняем изменения
                $user->gallery = json_encode($userGallery);
                $user->save();
                // Удаляем временный WebP файл
                unlink($outputWebPPath);
            } else if (Str::contains($request->file('file')->getMimeType(), 'video')) {
                $path = "https://dspt7sohnkg6q.cloudfront.net/" . Storage::disk('s3')->putFile($user->email . '/portfolio', $request->file('file'));
                $userGallery = $user->gallery ? json_decode($user->gallery, true) : []; // Распарсим текущую галерею
                $userGallery[] = $path; // Добавим новый путь в массив галереи
                $user->gallery = json_encode($userGallery); // Закодируем массив обратно в JSON
                $user->save(); //
                /* $video = $request->file('file');
                $tempPath = $video->getPathname();

                // Создаем имя файла для сжатого видео
                $compressedVideoPath = $tempPath . '_compressed.mp4';

                // Используем FFMpeg для сжатия видео
                FFMpeg::fromDisk('local')  // Используем временное локальное хранилище
                    ->open($video->getClientOriginalName())
                    ->export()
                    ->toDisk('local')  // Можно сохранить в локальном хранилище для дальнейшей загрузки
                    ->inFormat(new \FFMpeg\Format\Video\X264('aac'))
                    ->resize(1280, 720)  // Изменяем разрешение видео
                    ->save($compressedVideoPath);

                // Загружаем сжатое видео в S3
                $s3Path = $user->email . '/portfolio/' . pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME) . '_compressed.mp4';
                Storage::disk('s3')->put($s3Path, file_get_contents($compressedVideoPath));

                // Получаем URL загруженного видео
                $videoUrl = Storage::disk('s3')->url($s3Path);

                // Обновляем галерею пользователя
                $userGallery = $user->gallery ? json_decode($user->gallery, true) : [];
                $userGallery[] = $videoUrl;

                // Сохраняем изменения
                $user->gallery = json_encode($userGallery);
                $user->save();

                // Удаляем временный файл
                unlink($compressedVideoPath); */
            } else {
                return back()->withErrors(['file' => 'Файл должен быть изображением или видео.']);
            }
        }

        return back();
    }


    public function deletePortfolioItem(Request $request)
    {
        $user = Auth::user();
        $userGallery = json_decode($user->gallery, true);
        $cleanPath = str_replace(["https://promob.s3.amazonaws.com/", "https://dspt7sohnkg6q.cloudfront.net/"], "", $request->fileName);
        if (in_array($request->fileName, $userGallery)) {
            $result = array_diff($userGallery, [$request->fileName]);
            if (Storage::disk('s3')->exists($cleanPath)) {
                $response = Storage::disk('s3')->delete($cleanPath);
            } else {
                return back()->withErrors(['file' => 'Файл не был найден.']);
            }
            $user->update([
                'gallery' => json_encode($result),
            ]);
        }
        return back();
    }
}
