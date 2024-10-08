<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SmsController;
use App\Mail\VerificationMail;
use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function register_update(Request $request)
    {
        if (strpos($request->email, '@') == false) {
            $existingUser = User::where('tel', $request->email)->first();
        } else {
            $existingUser = User::where('email', $request->email)->first();
        }

        $corrent_city = session('city');
        if (!$corrent_city) {
            $corrent_city = City::get()->first();
        }

        $users = $corrent_city->users()->paginate(20);

        $role = $existingUser->role ? $existingUser->role : null;

        $param = [
            'email' => $request->tel,
            'active_modal_error' => true,
            'corrent_city' => $corrent_city,
            'users' => $users,
        ];

        if ($existingUser->is_verified) {
            return view('app.index', $param);
        }

        $verification_code = rand(1000, 9999);

        if (strpos($request->email, '@') == false) {
            User::updateOrCreate(
                ['tel' => $request->tel],
                [
                    'tel' => $request->tel,
                    'verification_code' => $verification_code,
                    'role' => $role,
                ]
            );

            $smsController = new SmsController();

            $smsResponse = $smsController->sendSMS($request->tel, $verification_code);


            if ($smsResponse->getStatusCode() != 200) {
                return back()->withErrors(['sms' => 'Failed to send SMS. Please try again.']);
            }
        } else {
            $user = User::updateOrCreate(
                ['email' => $request->email],
                [
                    'email' => $request->email,
                    'verification_code' => $verification_code,
                    'role' => $role,
                ]
            );

            Mail::to($user->email)->send(new VerificationMail($verification_code));
        }

        $params = [
            'email' => $request->email,
            'active_modal' => 'graph-modal-open fade animate-open',
            'active_div' => 'is-open',
            'corrent_city' => $corrent_city,
            'users' => $users,
        ];

        return view('app.index', $params);
    }

    public function register_edit(Request $request)
    {
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            $existingUser->delete();
        }

        return redirect()->to('/');
    }

    public function register(Request $request)
    {
        $corrent_city = session('city');
        if (!$corrent_city) {
            $corrent_city = City::get()->first();
        }

        $users = $corrent_city->users()->paginate(20);

        $param = [
            'email' => $request->email,
            'active_modal_error' => true,
            'corrent_city' => $corrent_city,
            'users' => $users,
        ];

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser && $existingUser->is_verified) {
            return view('app.index', $param);
        }

        $verification_code = rand(1000, 9999);

        $user = User::updateOrCreate(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'verification_code' => $verification_code,
                'role' => $request->role,
                'gallery' => json_encode([]),
                'cities_id' => 1,
            ]
        );

        // Отправка email
        Mail::to($user->email)->send(new VerificationMail($verification_code));

        $params = [
            'email' => $request->email,
            'active_modal' => 'graph-modal-open fade animate-open',
            'active_div' => 'is-open',
            'corrent_city' => $corrent_city,
            'users' => $users,
        ];

        return view('app.index', $params);
    }

    public function register_sms(Request $request)
    {
        $request->validate([
            'tel' => 'required',
        ]);
        $corrent_city = session('city');
        if (!$corrent_city) {
            $corrent_city = City::get()->first();
        }
        $users = $corrent_city->users()->paginate(20);
        $city_id = $corrent_city->getKey();

        $params = [
            'tel' => $request->tel,
            'corrent_city' => $corrent_city,
            'users' => $users,
        ];

        $existingUser = User::where('tel', $request->tel)->first();
        if ($existingUser && $existingUser->is_verified &&  $existingUser->email) {
            // return view('app.index', [...$params, 'active_modal_error' => true,]);
            return back()->withErrors(['user' => 'Данный аккаунт уже существует.
Пожалуйста войдите или используйте другой номер для регистрации']);
        }

        $verification_code = rand(1000, 9999);
        $smsController = new SmsController();
        try {
            $smsResponse = $smsController->sendSMS($request->tel, $verification_code);
            if ($smsResponse->getData()->error) {
                return back()->withErrors(['sms' => $smsResponse->getData()->error]);
            };
        } catch (\Exception $e) {
            return back()->withErrors(['sms' => $e->getMessage()]);
        }
        User::updateOrCreate(
            ['tel' => $request->tel],
            [
                'tel' => $request->tel,
                'verification_code' => $verification_code,
                'role' => $request->role,
                'gallery' => json_encode([]),
                'cities_id' => $city_id,
            ],
        );
        return view('app.index', [...$params, 'active_modal' => 'graph-modal-open fade animate-open', 'active_div' => 'is-open',]);
    }

    public function register_view()
    {
        $corrent_city = session('city');
        if (!$corrent_city) {
            $corrent_city = City::get()->first();
        }

        $params = [
            'corrent_city' => $corrent_city,
        ];

        return view('auth.register', $params);
    }
    public function send_sms_code(Request $request)
    {
        $request->validate([
            'tel' => 'required',
        ]);
    }
}
