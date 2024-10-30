<?php

use App\Http\Controllers\Api\ApiAppInformController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiCitycontroller;
use App\Http\Controllers\Api\ApiPortfolioController;
use App\Http\Controllers\Api\ApiUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('/hello', [ApiAuthController::class, 'hello']);


// Авторизация и регистрация

Route::get('/getSms', [ApiAuthController::class, 'getSmsCode']);
Route::get('/verifyEmail', [ApiAuthController::class, 'verifyEmail'])->name('verify-email');
Route::get('/logout', [ApiAuthController::class, 'logout']);
Route::post('/registrationWithCode', [ApiAuthController::class, 'registrationWithSms']);
Route::post('/setNewPassword', [ApiAuthController::class, 'setNewPassword']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/registerWithEmail', [ApiAuthController::class, 'registerWithEmail']);
Route::post('/deleteAccount', [ApiAuthController::class, 'deleteAccount']);
///Нужно сделать сброс пароля для двух ролей


// Изменеие портфолио и информации о пользователе
Route::post('/savePortfolioItem', [ApiPortfolioController::class, 'savePortfolioItem']);
Route::post('/editUserInfo', [ApiUserController::class, 'editUserInfo']);
Route::post('/uploadAvatar', [ApiUserController::class, 'uploadAvatar']);

//Получение данных о пользователях
Route::get('/getAllUsers', [ApiUserController::class, 'getAllUsers']);
Route::get('/getUsersWithPagination', [ApiUserController::class, 'getUsersWithPagination']);
Route::get('/getUsersWithCity', [ApiUserController::class, 'getUsersWithCity']);
Route::get('/getUsersWithCategory', [ApiUserController::class, 'getUsersWithCategory']);
Route::get('/getUsersWithCityAndCategory', [ApiUserController::class, 'getUsersWithCityAndCategory']);


// Получение Данных о городе, категориях
Route::get('/getAllCities', [ApiCitycontroller::class, 'getCit']);
Route::get('/getAllCategories', [ApiAppInformController::class, 'getAllCategories']);
