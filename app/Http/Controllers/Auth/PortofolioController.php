<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
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

                    Log::info('Путь к файлу после сохранения', ['path' => $path]);

                    $user->photos = $path;

                    $user->save();

                    Log::info('Данные пользователя успешно обновлены', ['user_id' => $user->id]);

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

                if (Str::contains($mimeType, 'image') && $fileSize > 10 * 1024 * 1024) return false;

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
                    Log::info('Текущий пользователь', ['user_id' => $user->id, 'email' => $user->email]);

                    $path = 'storage/' . $file->storeAs('images/' . $user->email, $file->getClientOriginalName(), 'public');

                    Log::info('Путь к файлу после сохранения', ['path' => $path]);

                    $userGallery[] = $path;

                    if (Str::lower($file->getClientOriginalExtension()) === 'heic') {
                        $extension = 'jpg';

                        HeicToJpg::convert($path)->saveAs(preg_replace('/\.heic$/i', ".$extension", $path));

                        File::delete(public_path($path));

                        $path = Str::replace($file->getClientOriginalExtension(), $extension, $path);
                    } else {
                        $extension = $file->getClientOriginalExtension();
                    }

                    if (Str::contains($mimeType, 'image')) {
                        $resizeImagePath = 'storage/' . 'images/' . $user->email . '/' . pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)  . '_350x225.' . $extension;

                        $manager = new ImageManager(Driver::class);

                        $image = $manager->read($path);

                        $image->scale('350', '225');

                        $image->save($resizeImagePath);

                        $userGallery[] = $resizeImagePath;
                    }

                    $user->gallery = json_encode($userGallery);

                    $user->save();

                    Log::info('Данные пользователя успешно обновлены', ['user_id' => $user->id]);

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

    public function deletePortfolioItem(Request $request)
    {
        $user = auth()->user();

        $userGallery = json_decode($user->gallery, true);

        if ($fileName = Str::replace('#t=0.001', '', $request->json('fileName'))) {
            $result = array_filter($userGallery, function ($galleryItem) use ($fileName) {
                return !strpos($galleryItem, $fileName);
            });
        }

        $user->update([
            'gallery' => json_encode($result),
        ]);
    }
}
