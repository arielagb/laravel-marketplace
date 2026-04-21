<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    public function showSignUp(){
        if(Auth::check()){
            return $this->redirectbyRole();
        }
        return view('auth.register');
    }

    public function showFormLogin(){
        if(Auth::check()){
            return $this->redirectbyRole();
        }
        return view('auth.login');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if(Auth::attempt($request->only('email', 'password'))){
            return $this->redirectbyRole();
        }

        return back()->withErrors(['email'=> 'Email or password is wrong']);
    }

    public function signUp(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        Auth::login($user);
        return $this->redirectbyRole();//apres un return, tout code ecrit est mort 
        // return back()->widh('success', 'You have signed up sucessfully.');
    }

    private function redirectByRole(){
    $role = auth()->user()->role->label;

    if ($role === 'Admin') {
        return redirect()->route('dashboard_admin');
    }
    if ($role === 'Seller') {
        $shop = auth()->user()->shop;
        // Si le seller n'a pas encore de boutique, onboarding obligatoire
        if (!$shop) {
            return redirect()->route('seller.onboarding');
        }
        // Si la boutique est en attente de validation
        if ($shop->status === 'pending') {
            return redirect()->route('seller.pending');
        }
        return redirect()->route('dashboard_seller');
    }
    if ($role === 'Buyer') {
        return redirect()->route('dashboard_buyer');
    }
}

    public function logout(){
    Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
}
}
