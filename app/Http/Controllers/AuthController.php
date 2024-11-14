<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(Request $request): View
    {
        if (Auth::check()) {
            return redirect()->intended("dashbord");
        }

        return view("auth.login");
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            "email" => ["required", "email"],
            "password" => ["required"],
        ]);

        if (
            Auth::attemptWhen($credentials, function (User $user) {
                return $user->is_active;
            })
        ) {
            $request->session()->regenerate();

            return redirect()->intended("dashboard");
        }

        return back()
            ->withErrors([
                "email" => "The provided credentials do not match our records.",
            ])
            ->onlyInput("email");
    }
}
