<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{

    public function showAdminLoginForm(Request $request)
    {
        // dd($request);
        return view('auth.login'); // make this view (see step 3)
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = (bool) $request->boolean('remember');

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            // change to your admin landing route/path:
            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'email' => 'Invalid admin credentials.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return view('logout');
    }
}
