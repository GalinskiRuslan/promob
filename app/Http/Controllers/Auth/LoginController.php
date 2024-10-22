<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordConfirmationMail;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Mockery\Exception;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/registration/video';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            session()->put('password', $request->password);
            return redirect('/');
        }
        return back()->withErrors(['email' => 'Неверные данные для входа.']);
    }

    public function resetPasswordConfirmation(Request $request)
    {
        return back()->withErrors(['email' => 'Неверные данные для входа.']);
        $request->validate([
            'email' => ['required', 'email', 'exists:users'],
        ]);

        $user = User::query()->where('email', $request->email)->first();

        $confirmationCode = Hash::make(Str::random(5));


        if (Mail::to($user->email)->send(new ResetPasswordConfirmationMail($confirmationCode))) {
            session()->put('reset-password-confirmation-code', $confirmationCode);

            return response()->json([
                'status' => 'success',
                'email' => $user->email,
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users'],
        ]);

        if (session('reset-password-confirmation-code') !== $request->confirmationCode) {
            return response()->json([
                'status' => 'error',
                'message' => 'Вы ввели неверный код подтверждения!',
            ]);
        }

        $user = User::query()->where('email', $request->email)->first();

        $password = Str::random(rand(8, 10));

        if (Mail::to($request->email)->send(new ResetPasswordMail($password))) {
            $user->update([
                'password' => bcrypt($password),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Ваш пароль был успешно восстановлен и отправлен вам на почту!',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Произошла ошибка при отправке пароля!',
        ]);
    }
}
