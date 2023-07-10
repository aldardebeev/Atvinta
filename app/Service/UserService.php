<?php
namespace App\Service;

use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function signUp(array $data)
    {
        $existingUser = $this->userRepository->findByEmail($data['email']);

        if ($existingUser) {
            return [
                'success' => false,
                'errors' => ['email' => 'Такой пользователь уже зарегистрирован'],
            ];
        }

        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if ($user) {
            Auth::login($user);
            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
            'errors' => ['formError' => 'Произошла ошибка при сохранении пользователя'],
        ];
    }

    public function signIn(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            return [
                'success' => true,
            ];
        }
        return [
            'success' => false,
            'errors' => ['email' => 'Не удалось авторизоваться'],
        ];
    }
}
