<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use WebPConvert\WebPConvert;

class ConvertImageToWebP implements ShouldQueue
{
    use Queueable;
    protected $file;
    protected $user;

    public function __construct($file, $user)
    {
        $this->file = $file;
        $this->user = $user;
    }

    public function handle()
    {
        dd("ZAEBAL!");
        $tempPath = storage_path('app/' . $this->file);

        $outputWebPPath = $tempPath . '.webp';

        // Конвертируем изображение в WebP
        WebPConvert::convert($tempPath, $outputWebPPath, [
            'quality' => 85,
        ]);

        if ($this->user->email) {
            $webpPath = $this->user->email . '/portfolio/' . pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
        } else {
            $webpPath = $this->user->phone . '/portfolio/' . pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';
        }

        if ($this->user->photos) {
            $clearPath = str_replace(["https://dspt7sohnkg6q.cloudfront.net/", "https://promob.s3.amazonaws.com/"], "", $this->user->photos);
            Storage::disk('s3')->delete($clearPath);
        }

        // Загрузка WebP на S3
        Storage::disk('s3')->put($webpPath, file_get_contents($outputWebPPath), 'public');

        // Сохранение ссылки
        $webpUrl = "https://dspt7sohnkg6q.cloudfront.net/" . $webpPath;
        $this->user->photos = $webpUrl;
        $this->user->save();
    }
}
