<?php

use App\Http\Controllers\Api\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/hello', function () {
    return 'hello world';
});


// Авторизация и регистрация

Route::get('/getSms', [ApiAuthController::class, 'getSmsCode']);
