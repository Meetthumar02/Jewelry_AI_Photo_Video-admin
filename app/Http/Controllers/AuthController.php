<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Log;
use App\Mail\VerifyEmailMail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $admin = \App\Models\Admin::where('email', trim($request->email))->first();

        if (!$admin) {
            return back()->with('error', 'Email not found');
        }

        if (!\Illuminate\Support\Facades\Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Password incorrect');
        }

        session()->regenerate();

        session([
            'ADMIN_LOGIN' => true,
            'ADMIN_ID' => $admin->id,
        ]);

        return redirect('/dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
