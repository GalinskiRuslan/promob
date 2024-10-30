<?php

use App\Http\Controllers\Api\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/hello', [ApiAuthController::class, 'hello']);


// Авторизация и регистрация

Route::get('/getSms', [ApiAuthController::class, 'getSmsCode']);
Route::post('/registrationWithCode', [ApiAuthController::class, 'registrationWithSms']);
Route::post('/setNewPassword', [ApiAuthController::class, 'setNewPassword']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/logout', [ApiAuthController::class, 'logout']);
Route::post('/registerWithEmail', [ApiAuthController::class, 'registerWithEmail']);


Route::get('/cities', [ApiAuthController::class, 'getAllCities']);
