<?php

use App\Http\Controllers\app\AppController;
use App\Http\Controllers\app\CityController;
use App\Http\Controllers\app\comments\CommentController;
use App\Http\Controllers\app\StatisticController;
use App\Http\Controllers\app\UpdateInfoExecutorController;
use App\Http\Controllers\app\UserViewController;
use App\Http\Controllers\app\VideoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PortofolioController;
use App\Http\Controllers\Auth\UpdateInfoController;
use App\Http\Controllers\Auth\VerifyController;
use App\Http\Controllers\search\SearchController;
use App\Http\Controllers\SmsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', [AppController::class, 'index'])->name('home');

Route::get('/login', function () {
    return redirect()->route('home');
});

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/register', function () {
    return redirect()->route('home');
});

Route::prefix('registration')->group(function () {
    Route::get('/video', [VideoController::class, 'index'])->name('video');

    Route::get('/update-info', [UpdateInfoController::class, 'index'])->name('update-info');
    Route::post('/update-info', [UpdateInfoController::class, 'update']);

    Route::middleware(['auth'])->group(function () {
        Route::get('/about-executor', [UpdateInfoExecutorController::class, 'about_executor'])->name('about_executor');
        Route::post('/about-executor', [UpdateInfoExecutorController::class, 'update']);

        Route::get('/portfolio-edit', [PortofolioController::class, 'index'])->name('portfolio');
        Route::post('/portfolio-edit-test', [PortofolioController::class, 'store_gallery'])->name('portfolio_gallery');
        Route::delete('/portfolio-remove', [PortofolioController::class, 'deletePortfolioItem'])->name('portfolio_delete');
    });
    Route::post('/portfolio-edit', [PortofolioController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'register_view'])->name('register_my');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/register-sms', [RegisterController::class, 'register_sms'])->name('register-sms');
    Route::post('/register-update', [RegisterController::class, 'register_update'])->name('register_update');
    Route::post('/register-edit', [RegisterController::class, 'register_edit'])->name('register_edit');

    Route::post('/verify', [VerifyController::class, 'verify'])->name('verify');
});
Route::get('/search', [\App\Http\Controllers\ajax\SearchController::class, 'search'])->name('search.city');
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
Route::get('/comments/{id}', [CommentController::class, 'index'])->name('comments.index');
Route::get('/statistics', [StatisticController::class, 'index'])->name('statistic');
Route::get('/{city}', [CityController::class, 'index'])->name('city');
Route::get('/{city}/search', [SearchController::class, 'search'])->name('search');
Route::get('/{city}/{category}', [CityController::class, 'city_category'])->name('city_category');
Route::get('/{city}/{category}/search', [SearchController::class, 'search'])->name('search_category');

Route::get('/executor/user/{id}', [UserViewController::class, 'index'])->name('user_view');
Route::get('/card-edit/user/{id}', [UserViewController::class, 'edit'])->name('user_edit');
Route::delete('/card-edit/user/remove-avatar', [UserViewController::class, 'deleteAvatar'])->name('delete_avatar');
Route::post('/update-photo-avatar', [UserViewController::class, 'save_new_avatar'])->name('update_photo_avatar');

Route::post('/update-info-portfolio', [UserViewController::class, 'update_first'])->name('update_first');
Route::post('/update-info-portfolio-dawn', [UserViewController::class, 'update_second'])->name('update_second');

Route::post('/reset-password', [LoginController::class, 'resetPassword'])->middleware('guest')->name('reset_password');
Route::post('/reset-password-confirmation', [LoginController::class, 'resetPasswordConfirmation'])->middleware('guest')->name('reset_password_confirmation');

Route::delete('/remove-profile', [UserViewController::class, 'deleteProfile'])->middleware('auth')->name('user.delete-profile');
