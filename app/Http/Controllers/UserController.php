<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showSignUp(){
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function showFormLogin(){
        if(Auth::check()){
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if(Auth::attempt($request->only('email', 'password'))){
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['email'=> 'Email or password is wrong']);
    }

    public function signUp(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',

        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back()->widh('success', 'You have signed up sucessfully.');
    }

    public function logout(){
        Auth::logout();
        return redirect('/login')->route('login');
    }
}
