<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
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
                'name' => 'required|string',
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
        try {
            $request->validate([
                'file' => 'required|mimes:jpg,jpeg,png,jpg,svg,heic,webp',
            ]);
            $uploadedFile = Cloudinary::upload($request->file('file')->getRealPath(), [
                'folder' => $user->email . '/portfolio',
                'format' => 'webp',
                'quality' => '80',
            ]);
            // Получаем URL изображения
            $uploadedFileUrl = $uploadedFile->getSecurePath();
            $publicId = $uploadedFile->getPublicId();
            $user->photos = $uploadedFileUrl . '?public_id=' . $publicId;
            $user->save();
            return response()->json(['message' => 'Данные успешно обновлены'], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200, [],  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    public function getUsersWithPagination(Request $request)
    {
        // Получаем номер страницы и количество элементов на странице из запроса (по умолчанию 10)
        $perPage = $request->input('per_page', 10);
        $users = User::paginate($perPage, ['*'], 'page', $request->input('page', 1));

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
        $users = User::where('cities_id', $city)->paginate($perPage, ['*'], 'page', $request->input('page', 1));
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
        $users = User::whereJsonContains('categories_id', [$category])->paginate($perPage, ['*'], 'page', $request->input('page', 1));
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
}
