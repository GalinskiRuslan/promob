<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maestroerror\HeicToJpg;

class UserViewController extends Controller
{
    public function index(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        $user->statistic()->increment('view_count');

        $corrent_city = session('city');
        if (!$corrent_city) {
            $corrent_city = City::get()->first();
        }

        $params = [
            'user' => $user,
            'corrent_city' => $corrent_city,
        ];

        return view('app.user', $params);
    }

    public function edit()
    {
        $corrent_city = session('city');
        if (!$corrent_city) {
            $corrent_city = City::get()->first();
        }

        $categories = Category::all();

        $cities = City::all();

        $user = Auth::user();

        $params = [
            'corrent_city' => $corrent_city,
            'user' => $user,
            'categories' => $categories,
            'cities' => $cities,
        ];

        return view('auth.edit', $params);
    }


    public function update_first(Request $request)
    {
        $user = Auth::user();
        if ($request->hasFile('file')) {
            $validated = $request->validate([
                'file' => 'required|mimes:jpg,jpeg,png,jpg,svg,heic|max:100000',
            ]);
            $file = $request->file('file');
            if ($validated) {
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
            }
        }

        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'surname_2' => 'required|string',
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
            'tel' => 'required|string',
            'cost_from' => 'required|numeric|min:1|max:500000000',
            'cost_up' => 'required|numeric|min:10|max:5000000000',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::find(Auth::id());

        $languages = [];

        if ($request->rus == 'on') {
            $languages[] = 'rus';
        }

        if ($request->kaz == 'on') {
            $languages[] = 'kaz';
        }

        if ($request->en == 'on') {
            $languages[] = 'en';
        }

        $data = [
            'name' => $request->name,
            'surname' => $request->surname,
            'surname_2' => $request->surname_2,
            'nickname' => $request->nickname,
            'nickname_true' => $request->nickname_true ? true : false,
            'site' => $request->site,
            'instagram' => $request->instagram,
            'whatsapp' => $request->whatsapp,
            'tel' => $request->tel,
            'email' => $request->email,
            'categories_id' => $request->categories_id,
            'cost_from' => $request->cost_from,
            'cost_up' => $request->cost_up,
            'details' => $request->details,
            'about_yourself' => $request->about_yourself,
            'cities_id' => $request->cities_id,
            'language' => $languages,
        ];

        if ($request->filled('password')) {
            Log::info('Password is being updated');
            $data['password'] = Hash::make($request->password);
        } else {
            Log::info('Password is not being updated');
        }

        $user->fill($data);

        $user->update();


        if (auth()->user()->role === 'executor') {
            return redirect()->route('user_view', $user->id);
        } else {
            return redirect()->to('/');
        }
    }

    public function update_second(Request $request)
    {
        $user = User::find(Auth::id());

        $request->validate([
            'cost_from' => 'required|numeric|min:1|max:500000',
            'cost_up' => 'required|numeric|min:10|max:500000',
        ]);
        $user->categories_id = $request->categories_id;
        $user->cost_from = $request->cost_from;
        $user->cost_up = $request->cost_up;
        $user->details = $request->details;
        $user->about_yourself = $request->about_yourself;
        $user->cities_id = $request->cities_id;

        $languages = [];
        if ($request->rus == 'on') {
            $languages[] = 'rus';
        }
        if ($request->kaz == 'on') {
            $languages[] = 'kaz';
        }
        if ($request->en == 'on') {
            $languages[] = 'en';
        }
        $user->language = $languages;
        $user->save();

        return redirect()->back();
    }

    public function save_new_avatar(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,jpg,svg,heic|max:100000',
        ]);
        $file = $request->file('file');
        $user = Auth::user();
        if ($user->photos) {
            $clearPath = str_replace("https://promob.s3.amazonaws.com/", "", $user->photos);
            Storage::disk('s3')->delete($clearPath);
        }
        if ($user->email) {
            $path = "https://promob.s3.amazonaws.com/" . Storage::disk('s3')->put($user->email . '/avatar', $file);
        } else {
            $path = "https://promob.s3.amazonaws.com/" . Storage::disk('s3')->put($user->tel . '/avatar', $file);
        }
        $user->photos = $path;
        $user->save();
        return redirect()->back();
    }

    public function deleteAvatar()
    {
        $user = Auth::user();
        $clearPath = str_replace("https://promob.s3.amazonaws.com/", "", $user->photos);
        Storage::disk('s3')->delete($clearPath);
        $user->photos = null;
        $user->save();
        return redirect()->back();
    }

    public function deleteProfile()
    {
        auth()->user()->delete();

        return redirect()->route('home');
    }
}
