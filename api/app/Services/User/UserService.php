<?php
declare(strict_types=1);

namespace App\Services\User;

use Repository\User\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {
    
    }

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
        return $this->userRepository->create($data);
    }

    public function updateUser(array $data, string $id, bool $isPatch = false)
    {
        return $this->userRepository->updateByID($id, $data);
    }

    public function getUserById($userId)
    {
        return $this->userRepository->findByID($userId);
    }

    public function deleteUserById($userId)
    {
        return$this->userRepository->deletedByID($userId);
    }
}
