<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PageController extends Controller
{
    public function home()
    {
        return redirect()->route('login');
    }

    public function login()
    {
        return Inertia::render('Login');
    }

    public function register()
    {
        return Inertia::render('Register');
    }

    public function forgotPassword()
    {
        return Inertia::render('ForgotPassword');
    }

    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }

    public function content()
    {
        return Inertia::render('Content');
    }

    public function user()
    {
        return Inertia::render('User');
    }
}
