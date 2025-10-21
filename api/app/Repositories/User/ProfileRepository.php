<?php
declare(strict_types=1);

namespace Repository\User;

use App\Models\Profile;
use Repository\BaseRepository;

class ProfileRepository extends BaseRepository
{
    const RESOURCE_NAME = 'profiles';

    public function model()
    {
        return Profile::class;
    }
}