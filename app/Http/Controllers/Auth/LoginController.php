<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function create()
    {
        if(auth()->check()){
            return redirect()->to(route('home'));
        }

        return view('login');
    }

    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials, ($request->remember ?? false))) {
            $request->session()->regenerate();

            return redirect()->intended();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function destroy(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}
