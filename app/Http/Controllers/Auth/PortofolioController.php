<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


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
                $uploadedFile = Cloudinary::upload($request->file('file')->getRealPath(), [
                    'folder' => $user->email . '/portfolio',
                    'format' => 'webp',
                    'quality' => '80',
                ]);
                $uploadedFileUrl = $uploadedFile->getSecurePath();
                $publicId = $uploadedFile->getPublicId();

                $userGallery = $user->gallery ? json_decode($user->gallery, true) : [];
                $userGallery[] = $uploadedFileUrl . '?public_id=' . $publicId;

                $user->gallery = json_encode($userGallery);
                $user->save();
            } else if (Str::contains($request->file('file')->getMimeType(), 'video')) {
                $path = "https://dspt7sohnkg6q.cloudfront.net/" . Storage::disk('s3')->putFile($user->email . '/portfolio', $request->file('file'));
                $userGallery = $user->gallery ? json_decode($user->gallery, true) : []; // Распарсим текущую галерею
                $userGallery[] = $path; // Добавим новый путь в массив галереи
                $user->gallery = json_encode($userGallery); // Закодируем массив обратно в JSON
                $user->save(); //
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
        if (Str::contains($request->fileName, ["https://promob.s3.amazonaws.com/", "https://dspt7sohnkg6q.cloudfront.net/"])) {
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
        } else {
            if (in_array($request->fileName, $userGallery)) {
                $result = array_diff($userGallery, [$request->fileName]);
                parse_str(parse_url($request->fileName)['query'], $queryParams);
                Cloudinary::destroy($queryParams['public_id']);
                $user->update([
                    'gallery' => json_encode($result),
                ]);
            }
        }
        return back();
    }
}
