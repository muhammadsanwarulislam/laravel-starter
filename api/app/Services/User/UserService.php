<?php

declare(strict_types=1);

namespace App\Services\User;

use Repository\User\UserRepository;
use App\Models\Language;
use App\Models\Translation;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function getUsers($requestData)
    {
        $offset         = $requestData['offset'] ?? 1;
        $limit          = $requestData['limit'] ?? 10;
        $option         = $requestData['option'] ?? 'list';
        $searchData     = $requestData['searchData'] ?? null;
        $searchFields   = $requestData['searchFields'] ?? null;

        $result = $this->userRepository->getAll($offset, $limit, $searchData, $searchFields, $option);

        return [
            'users'      => $result['result'],
            'pagination' => [
                'total'         => $result['total_count'],
                'per_page'      => $result['per_page'],
                'current_page'  => $result['current_page'],
                'last_page'     => $result['last_page'],
                'from'          => (($offset - 1) * $limit) + 1,
                'to'            => min($offset * $limit, $result['total_count'])
            ],
            'metadata'          => $this->userRepository->metadata($result['total_count'], 'success')
        ];
    }

    public function createUser(array $data)
    {
        // Extract translations from the data
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        // Ensure name is set from English translation if available
        if (!isset($data['name']) && isset($translations['name']['en'])) {
            $data['name'] = $translations['name']['en'];
        }

        // Create the user
        $user = $this->userRepository->create($data);

        // Save translations
        if (!empty($translations)) {
            $this->saveTranslations($user, $translations);
        }

        return $user;
    }

    public function updateUser(array $data, string $id, bool $isPatch = false)
    {
        // Extract translations from the data
        $translations = $data['translations'] ?? [];
        unset($data['translations']);

        // Ensure name is set from English translation if available
        if (!isset($data['name']) && isset($translations['name']['en'])) {
            $data['name'] = $translations['name']['en'];
        }

        // Update the user
        $user = $this->userRepository->updateByID($id, $data);

        // Save translations
        if (!empty($translations)) {
            $this->saveTranslations($user, $translations);
        }

        return $user;
    }

    protected function saveTranslations($user, $translations)
    {
        foreach ($translations as $attribute => $langValues) {
            foreach ($langValues as $langCode => $value) {
                $language = Language::where('code', $langCode)->first();

                if (!$language) {
                    continue;
                }

                Translation::updateOrCreate(
                    [
                        'translatable_type' => get_class($user),
                        'translatable_id'   => $user->id,
                        'language_id'       => $language->id,
                        'attribute'         => $attribute,
                    ],
                    [
                        'value' => $value,
                    ]
                );
            }
        }
    }

    public function getUserById($userId)
    {
        return $this->userRepository->findByID($userId);
    }

    public function deleteUserById($userId)
    {
        return $this->userRepository->deletedByID($userId);
    }
}
