<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiUserController extends Controller
{
    public function editUserInfo(Request $request)
    {
        try {
            // Попытка получить пользователя из токена
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }
        try {
            $request->validate([
                'file' => 'required|',
                'surname' => 'required|string',
                'surname_2' => 'string',
                'nickname' => 'required|string',

                'instagram' => 'nullable|string|regex:/^[a-zA-Z0-9\.\-\_]+$/',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'whatsapp' => 'nullable|string',
                'cost_from' => 'required|numeric|min:1|max:500000000',
                'cost_up' => 'required|numeric|min:10|max:5000000000',
                'details' => 'required|string|max:255|min:22',
                'about_yourself' => 'required|string|max:255|min:22',
                'cities_id' => 'required|numeric|exists:cities,id',
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
        try {
            $user->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'surname_2' => $request->surname_2,
                'nickname' => $request->nickname,
                'nickname_true' => $request->nickname_true ? true : false,
                'instagram' => $request->instagram,
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'cost_from' => $request->cost_from,
                'cost_up' => $request->cost_up,
                'details' => $request->details,
                'about_yourself' => $request->about_yourself,
                'cities_id' => $request->cities_id,
                'language' => $request->languages ? $request->languages : ['rus'],
            ]);
            return response()->json(['message' => 'Данные успешно обновлены'], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    public function uploadAvatar(Request $request)
    {
        try {
            // Попытка получить пользователя из токена
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        }
        if ($request->hasFile('file')) {
            if (Str::contains($request->file('file')->getMimeType(), 'image')) {
                try {
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
                } catch (Exception $e) {
                    return response()->json(['message' => $e->getMessage()], 400);
                }
            } else if (Str::contains($request->file('file')->getMimeType(), 'video')) {
                try {
                    $uploadedFile = Cloudinary::uploadVideo($request->file('file')->getRealPath(), [
                        'folder' => $user->email . '/portfolio',
                        'resource_type' => 'video',
                        'quality' => 'auto', // Автоматическая оптимизация качества
                        'fetch_format' => 'auto', // Автоматическая оптимизация формата
                    ]);
                    $path = $uploadedFile->getSecurePath();
                    $publicId = $uploadedFile->getPublicId();
                    $userGallery = $user->gallery ? json_decode($user->gallery, true) : []; // Распарсим текущую галерею
                    $userGallery[] = $path . '?public_id=' . $publicId; // Добавим новый путь в массив галереи
                    $user->gallery = json_encode($userGallery); // Закодируем массив обратно в JSON
                    $user->save(); //
                } catch (Exception $e) {
                    return response()->json(['message' => $e->getMessage()], 400);
                }
            } else {
                return response()->json(['message' => 'Неверный тип файла'], 400);
            }
        }
    }
    public function getAllUsers()
    {
        $users = User::where('role', 'executor')->where('photos', '!=', null)->where('cost_from', '!=', null)->get();
        foreach ($users as $user) {
            DB::table('table_statistics_for_executors')->updateOrInsert(
                ['user_id' => $user->id],           // Условие поиска записи
                ['view_count' => DB::raw('COALESCE(view_count, 0) + 1')] // Увеличиваем view_count
            );
            $user->comments = Comment::where('user_id', $user->id)->get();
        }
        return response()->json(['users' => $users], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public function getUsersWithPagination(Request $request)
    {
        // Получаем номер страницы и количество элементов на странице из запроса (по умолчанию 10)
        $perPage = $request->input('per_page', 10);
        $users = User::paginate($perPage, ['*'], 'page', $request->input('page', 1))->where('photos', '!=', null)->where('cost_from', '!=', null);
        foreach ($users as $user) {
            DB::table('table_statistics_for_executors')
                ->where('user_id', $user->id)
                ->increment('view_count');
            $user->comments = Comment::where('user_id', $user->id)->get();
        }
        return response()->json([
            'data' => $users->items(), // Массив пользователей
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total_pages' => $users->lastPage(),
                'total' => $users->total(),
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public function getUsersWithCity(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $city = $request->input('city');
        $users = User::where('cities_id', $city)->where('photos', '!=', null)->where('cost_from', '!=', null)->paginate($perPage, ['*'], 'page', $request->input('page', 1));
        foreach ($users as $user) {
            DB::table('table_statistics_for_executors')
                ->where('user_id', $user->id)
                ->increment('view_count');
            $user->comments = Comment::where('user_id', $user->id)->get();
        }
        return response()->json([
            'data' => $users->items(), // Массив пользователей
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total_pages' => $users->lastPage(),
                'total' => $users->total(),
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public function getUsersWithCategory(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $category = $request->input('category');
        $users = User::whereJsonContains('categories_id', [$category])->where('photos', '!=', null)->where('cost_from', '!=', null)->paginate($perPage, ['*'], 'page', $request->input('page', 1));
        foreach ($users as $user) {
            DB::table('table_statistics_for_executors')
                ->where('user_id', $user->id)
                ->increment('view_count');
            $user->comments = Comment::where('user_id', $user->id)->get();
        }
        return response()->json([
            'data' => $users->items(), // Массив пользователей
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total_pages' => $users->lastPage(),
                'total' => $users->total(),
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public function getUsersWithCityAndCategory(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $city = $request->input('city');
        $category = $request->input('category');
        $users = User::where('cities_id', $city)->whereJsonContains('categories_id', [$category])->paginate($perPage, ['*'], 'page', $request->input('page', 1));
        return response()->json([
            'data' => $users->items(), // Массив пользователей
            'meta' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total_pages' => $users->lastPage(),
                'total' => $users->total(),
            ],
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public function getUserInfo(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json(['user' => $user], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 401, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }
    public function changeContactsUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'surname' => 'required|string',
                'surname_2' => 'nullable|string',
                'nickname' => 'required|string',
                'nickname_true' => 'required|bool',
                'instagram' => 'nullable|string',
                'whatsapp' => 'required|string|min:10|max:13',
                'site' => 'nullable|string',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 401, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        try {
            $user->update($request->all());
            return response()->json(['message' => 'Контакты успешно изменены'], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }
    public function changeUserInfo(Request $request)
    {
        try {
            $request->validate([
                'cost_from' => 'required|numeric|min:1|max:500000000',
                'cost_up' => 'required|numeric|min:10|max:5000000000',
                'details' => 'required|string|max:255|min:22',
                'about_yourself' => 'required|string|max:255|min:22',
                'cities_id' => 'required|numeric|exists:cities,id',
                'language' => 'array',
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 401, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        try {
            $user->update($request->all());
            return response()->json(['message' => 'Информация успешно изменена'], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }
    public function addViewCount(Request $request) {}
    public function getStatistic(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 401, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        $statistic = DB::table('table_statistics_for_executors')->where('user_id', $user->id)->first();
        return response()->json(['statistic' => $statistic], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public function getComments(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['message' => $e->getMessage()], 401, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        $comments = Comment::where('target_user_id', $user->id)->get();
        return response()->json(['comments' => $comments], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
