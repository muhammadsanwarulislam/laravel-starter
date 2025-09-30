<?php
declare(strict_types=1);
namespace App\Services;

use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Events\LoggedInUserAccessTokenStoreEvent;
use Illuminate\Support\Facades\Hash;
use Repository\{
    User\UserRepository
};

class AuthService
{
    use JsonResponseTrait;
    public function __construct(protected UserRepository $userRepository)
    {

    }

    public function userSignin($validateData)
    {
        $incomingData = [
            'email'    => $validateData['email'],
            'password' => $validateData['password'],
        ];  

        $checkEmail = $this->userRepository->model()::where('email', $incomingData['email'])->first();

        if (!$checkEmail || !\Hash::check($incomingData['password'], $checkEmail->password)) {
            throw new \Exception('The provided credentials are incorrect.');
        }

        Auth::login($checkEmail);

        $user = [
            'access_token'  => $this->userRepository->generateAccessToken(Auth::user()),
            'user'          => Auth::user(),
        ];

        event(new LoggedInUserAccessTokenStoreEvent(
            data: [
                'user'          => Auth::user(),
                'access_token'  => $user['access_token'],
            ]
        ));

        return $user;
    }

    public function userRegistration($validateData)
    {
        return $this->userRepository->create($validateData);
    }

     public function userInformation()
        {
            $user = Auth::user();
            
            // Load translations for current locale
            $user->load(['translations' => function($query) {
                $query->whereHas('language', function($q) {
                    $q->where('code', app()->getLocale());
                });
            }]);

            return $user;
        }
}