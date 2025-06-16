<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    //
    public function index()
    {
        $users = auth()->user()->load('books');

        return view('admin.profile', compact('users'));
    }

    public function login() 
    {
        return view('auth.signin');
    }

    public function doLogin(Request $request) 
    {
        // dd($request->all());
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function register() 
    {
        return view('auth.signup');
    }

    public function doRegister(Request $request) 
    {
        $data = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()->route('signin')->with('success', 'Registration successful. Please login.');
    }

    public function logout() 
    {
        auth()->logout();

        return redirect()->route('signin');
    }
}
