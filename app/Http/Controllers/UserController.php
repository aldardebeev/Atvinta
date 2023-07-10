<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\SignInRequest;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;



class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Show the sign-up page.
     *
     * @return \Illuminate\View\View
     */
    public function showSignUp(): View
    {
        return view('signup');
    }

    /**
     * Handle the sign-up request.
     *
     * @param  \App\Http\Requests\CreateUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signUp(CreateUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $result = $this->userService->signUp($data);

        if ($result['success']) {
            return redirect()->route('home');
        }

        return redirect()->route('showSignUp')->withErrors($result['errors']);
    }

    /**
     * Handle the sign-in request.
     *
     * @param  \App\Http\Requests\SignInRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function signIn(SignInRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        $result = $this->userService->signIn($credentials);

        if ($result['success']) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors($result['errors']);
    }
}
