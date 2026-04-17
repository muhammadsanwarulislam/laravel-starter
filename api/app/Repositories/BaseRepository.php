<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getFiltered(
        ?string $search = null,
        ?array $searchFields = null,
        ?array $conditions = null,
        array $relations = []
    ) {
        $query = $this->model->with($relations);

        // Handle search
        if ($search && $searchFields) {
            $query->where(function ($q) use ($search, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        // Handle conditions
        if ($conditions) {
            $query->where($conditions);
        }

        return $query;
    }

    /**
     * Paginate filtered results
     */
    public function paginateFiltered(
        int $perPage = 15,
        ?string $search = null,
        ?array $searchFields = null,
        ?array $conditions = null,
        array $relations = [],
        string $sortField = 'created_at',
        string $sortOrder = 'desc'
    ): LengthAwarePaginator {
        $query = $this->getFiltered($search, $searchFields, $conditions, $relations);

        return $query->orderBy($sortField, $sortOrder)->paginate($perPage);
    }

    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    public function find(int $id, array $relations = []): ?Model
    {
        return $this->model->with($relations)->find($id);
    }

    public function findOrFail($id, array $relations = []): Model
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    public function findBy(array $criteria, array $relations = []): Collection
    {
        return $this->model->with($relations)->where($criteria)->get();
    }

    public function findOneBy(array $criteria, array $relations = []): ?Model
    {
        return $this->model->with($relations)->where($criteria)->first();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->find($id)->update($data);
    }

    public function updateBy(array $criteria, array $data): bool
    {
        return $this->model->where($criteria)->update($data);
    }

    public function delete($id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    public function deleteBy(array $criteria): bool
    {
        return $this->model->where($criteria)->delete();
    }

    public function paginate(int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        return $this->model->with($relations)->paginate($perPage);
    }

    public function count(array $criteria = []): int
    {
        return $this->model->where($criteria)->count();
    }

    public function exists(array $criteria): bool
    {
        return $this->model->where($criteria)->exists();
    }

    public function withTrashed(): self
    {
        $this->model = $this->model->withTrashed();
        return $this;
    }

    public function onlyTrashed(): self
    {
        $this->model = $this->model->onlyTrashed();
        return $this;
    }

    public function changeFieldType($value): bool
    {
        switch (gettype($value)) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int)$value;
            case 'float':
                return (float)$value;
            case 'string':
                return (int)$value;
            default:
                return $value;
        }
    }
}
