<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Service\SocialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Illuminate\Http\RedirectResponse;

class SocialController extends Controller
{
    /**
     * Redirect the user to the VKontakte authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    /**
     * Handle the callback from the VKontakte authentication.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(): RedirectResponse
    {
        /** @var \Laravel\Socialite\Two\User $user */
        $user = Socialite::driver('vkontakte')->user();
        $objSocial = new SocialService();
        if ($user = $objSocial->saveSocialData($user)) {
            Auth::login($user);
            return redirect()->route('home');
        }

        return back()->status(400);
    }
}
