<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showSignUp()
    {
        return view('signup');
    }

    public function signup(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if($user){
            Auth::login($user);
            return redirect()->route('home');

        }
        elseif (User::where('email', $user['email'])->exists()){
            return redirect()->route('showSignUp')->withErrors([
                'email' => 'Такой пользователь уже зарегистрирован'
            ]);
        }
        return redirect()->route('signin')->withErrors([
            'formError' => 'Произошла ошибка при сохранении пользователя'
        ]);

    }


    public function signin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Не удалось авторизоваться',
        ]);
    }



}
