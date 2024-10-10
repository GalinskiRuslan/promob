<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maestroerror\HeicToJpg;

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

    public function store(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $validated = $request->validate([
                    'file' => 'required|mimes:jpg,jpeg,png,jpg,gif,svg,heic|max:10000',
                ]);

                $file = $request->file('file');

                if ($validated) {
                    $user = Auth::user();

                    Log::info('Текущий пользователь', ['user_id' => $user->id, 'email' => $user->email]);

                    $path = 'storage/' . $file->storeAs('images/' . $user->email, $file->getClientOriginalName(), 'public');

                    if (Str::lower($file->getClientOriginalExtension()) === 'heic') {
                        $extension = 'jpg';

                        HeicToJpg::convert($path)->saveAs(preg_replace('/\.heic$/i', ".$extension", $path));

                        File::delete(public_path($path));

                        $path = Str::replace($file->getClientOriginalExtension(), $extension, $path);
                    }

                    $user->photos = $path;

                    $user->save();

                    if ($user->city && $user->city->alias) {
                        return redirect()->to('/' . $user->city->alias);
                    } else {
                        return response()->json(['error' => 'Город пользователя не найден или у города нет алиаса.'], 400);
                    }
                } else {
                    Log::error('Файл не прошел валидацию.', ['request' => $request->all()]);
                    return response()->json(['error' => 'Файл не прошел валидацию.'], 400);
                }
            } else {
                Log::error('Файл не был загружен.', ['request' => $request->all()]);
                return response()->json(['error' => 'Файл не был загружен.'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Ошибка при загрузке файла.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Ошибка загрузки файла.'], 500);
        }
    }
    public function store_gallery(Request $request)
    {

        try {
            if ($request->hasFile('file')) {
                $validated = $request->validate([
                    'file' => 'required|mimes:jpeg,png,jpg,svg,mp4,mov,avi,heic|max:102400'
                ]);

                $file = $request->file('file');

                $fileSize = $file->getSize();

                $galleryVideoCount = 0;

                $user = Auth::user();

                $userGallery = $user->gallery ? json_decode($user->gallery, true) : [];

                $mimeType = $file->getMimeType();

                if (Str::contains($mimeType, 'image') && $fileSize > 1000 * 1024 * 1024) return false;

                if (Str::contains($mimeType, 'video')) {
                    if ($fileSize > 102400 * 1024 * 1024) return false;

                    if ($userGallery) {
                        foreach ($userGallery as $galleryItem) {
                            if ($galleryVideoCount == 1) return false;

                            if (preg_match('/\.?(mp4|mov|avi)$/i', $galleryItem)) {
                                $galleryVideoCount++;
                            }
                        }
                    }
                }
                if ($validated) {
                    $path = 'storage/' . $file->storeAs('images/' . $user->email, $file->getClientOriginalName(), 'public');
                    $userGallery[] = $path;

                    $user->gallery = json_encode($userGallery);

                    $user->save();
                    if ($user->city && $user->city->alias) {
                        return redirect()->to('/' . $user->city->alias);
                    } else {
                        Log::error('Город пользователя не найден или у города нет алиаса.', ['user' => $user]);
                        return response()->json(['error' => 'Город пользователя не найден или у города нет алиаса.'], 400);
                    }
                } else {
                    Log::error('Файл не прошел валидацию.', ['request' => $request->all()]);
                    return response()->json(['error' => 'Файл не прошел валидацию.'], 400);
                }
            } else {
                Log::error('Файл не был загружен.', ['request' => $request->all()]);
                return response()->json(['error' => 'Файл не был загружен.'], 400);
            }
        } catch (\Exception $e) {
            // Логируем любые возникшие исключения
            Log::error('Ошибка при загрузке файла.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Ошибка загрузки файла.'], 500);
        }
    }
    public function savePortfolioItem(Request $request)
    {
        $user = Auth::user();
        if ($request->hasFile('file')) {
            $path = "https://promob.s3.amazonaws.com/" . Storage::disk('s3')->putFile($user->email . '/portfolio', $request->file('file'));
            $userGallery[] = $path;
            $user->gallery = json_encode($userGallery);
            $user->save();
        }
        return back();
    }

    public function deletePortfolioItem(Request $request)
    {
        $user = Auth::user();
        $userGallery = json_decode($user->gallery, true);
        $cleanPath = str_replace("https://promob.s3.amazonaws.com/", "", $request->fileName);
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
