<?php
declare(strict_types=1);

namespace App\Services\User;

use Repository\User\ProfileRepository;

class ProfileService
{
    public function __construct(protected ProfileRepository $profileRepository)
    {

    }

    public function getProfileByUserId($userId)
    {
        return $this->profileRepository->findOrFailByID($userId);
    }

    public function createProfile(array $data)
    {
        return $this->profileRepository->create($data);
    }
}
