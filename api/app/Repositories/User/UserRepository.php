<?php

declare(strict_types=1);

namespace Repository\User;

use App\Models\User;
use Repository\BaseRepository;
use Illuminate\Support\Facades\Auth;

class UserRepository extends BaseRepository
{
    const RESOURCE_NAME = 'users';
    const REGISTER_API_ENDPOINT_NAME = 'signup';
    const LOGIN_API_ENDPOINT_NAME = 'signin';
    const CURRENT_API_ENDPOINT_NAME = 'current-user';
    const LOGOUT_API_ENDPOINT_NAME = 'logout';

    public function model()
    {
        return User::class;
    }

    protected function applyDefaultCriteria($query)
    {
        parent::applyDefaultCriteria($query);
        $query->where('id', '<>', Auth::id());
    }

    protected function getSearchFields()
    {
        return ['name', 'email'];
    }

    public function generateAccessToken(User $user): string
    {
        return $user->createToken('authToken')->plainTextToken;
    }

    public function updateOrCreate(string $email, array $modelData)
    {
        $existingRecord = $this->model()::where('email', $email)->first();

        if ($existingRecord) {
            $existingRecord->update($modelData);
        } else {
            $this->model()::create(array_merge(['email' => $email], $modelData));
        }
    }

    public function create(array $modelData)
    {
        if (!isset($modelData['name']) && isset($modelData['translations']['name']['en'])) {
            $modelData['name'] = $modelData['translations']['name']['en'];
        }

        return parent::create($modelData);
    }

    public function updateByID($id, array $modelData)
    {
        if (!isset($modelData['name']) && isset($modelData['translations']['name']['en'])) {
            $modelData['name'] = $modelData['translations']['name']['en'];
        }

        return parent::updateByID($id, $modelData);
    }
}
