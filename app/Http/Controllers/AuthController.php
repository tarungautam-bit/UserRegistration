<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    public function registerform(){
        return view('auth.register');
    }

    public function home(){
        return view('home');
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('home');
        }

        return redirect()->route('loginpage')->withError('Invalid email or password');
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), 
        ]);
        if(Auth::attempt($request->only('email','password'))){
            return redirect('home');
         }

         return redirect()->route('registerpage')->withError('Invalid email or password');
    }

    public function logout(){
        Session::flush();
        Auth::logout();

        return redirect('/');

    }
}
