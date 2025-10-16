<?php
declare(strict_types=1);

namespace Repository;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    abstract function model();

    public function getAll($offset, $limit, $searchData = null, $searchFields = null, $option = 'list')
    {
        $query = $this->model()::query();
        $this->applyDefaultCriteria($query);

        switch ($option) {
            case 'search':
                if ($searchData && $searchFields) {
                    $this->applySearchCriteria($query, $searchData, $searchFields);
                }
                break;
            case 'list':
            default:
                break;
        }

        $totalCount = $query->count();

        if ($limit > 0) {
            $result = $query->offset(($offset - 1) * $limit)
                          ->limit($limit)
                          ->get();
        } else {
            $result = $query->get();
        }

        if ($result->isEmpty()) {
            throw new \RuntimeException('No records found.');
        }

        return [
            'result'        => $result,
            'total_count'   => $totalCount,
            'current_page'  => $limit > 0 ? ceil($offset / $limit) + 1 : 1,
            'per_page'      => $limit,
            'last_page'     => $limit > 0 ? ceil($totalCount / $limit) : 1
        ];
    }

    protected function applyDefaultCriteria($query)
    {
        $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    protected function applySearchCriteria($query, $searchData, $searchFields)
    {
        $searchFieldsArray = explode(',', $searchFields);
        
        $query->where(function ($q) use ($searchFieldsArray, $searchData) {
            foreach ($searchFieldsArray as $field) {
                $field = trim($field);
                
                // Handle boolean fields specially
                if (in_array($field, ['is_active', 'is_default'])) {
                    if ($searchData === '1' || strtolower($searchData) === 'true' || strtolower($searchData) === 'active') {
                        $q->orWhere($field, true);
                    } elseif ($searchData === '0' || strtolower($searchData) === 'false' || strtolower($searchData) === 'inactive') {
                        $q->orWhere($field, false);
                    }
                } else {
                    $q->orWhere($field, 'like', '%' . $searchData . '%');
                }
            }
        });
    }

    public function metadata($totalCount, $responseType)
    {
        return [
            'API Version'       => '1.0.1',
            'Response Time'     => date('Y-m-d H:i:s'),
            'Data Response Type'=> $responseType,
            'Total Records'     => $totalCount,
            'Content Type'      => 'application/json',
        ];
    }

    public function findByID($id): Model
    {
        $record = $this->model()::find($id);
        if (!$record) {
            throw new \Exception("Record with ID {$id} not found.");
        }
        return $record;
    }

    public function findOrFailByID($id): Model
    {
        return $this->model()::findOrFail($id);
    }

    public function create(array $modelData)
    {
        return $this->model()::create($modelData);
    }

    public function updateByID($id, array $modelData)
    {
        $model = $this->findOrFailByID($id);
        $model->update($modelData);
        return $model->fresh();
    }

    public function updateByModelCondition($condition, $field, $value)
    {
        return $this->model()::where($condition)->update([$field => $value]);
    }

    public function deletedByID($id)
    {
        $model = $this->findOrFailByID($id);
        return $model->delete();
    }
}