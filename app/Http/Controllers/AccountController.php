<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function view()
    {
        return view('account.profile');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'is_email_notifications_active' => 'nullable',
        ]);

        $user = $request->user();

        if(!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if($request->has('is_email_notifications_active')) {
            $user->is_email_notifications_active = true;
        } else {
            $user->is_email_notifications_active = false;
        }

        $user->update($request->only('name', 'email'));

        return redirect()->route('profile')->with('success', __('Profile updated!'));
    }

    public function meteo(Request $request)
    {
        $request->validate([
            'meteo_city' => 'required|string|max:255',
            'meteo_lat' => 'required|numeric',
            'meteo_lon' => 'required|numeric',
        ]);

        $user = $request->user();

        $user->update($request->only('meteo_city', 'meteo_lat', 'meteo_lon'));

        Cache::forget('weather'.$user->id);

        return redirect()->route('profile')->with('success', __('Meteo updated!'));
    }
}
