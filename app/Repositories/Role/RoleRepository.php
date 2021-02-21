<?php

namespace Repository\Role;
use App\Models\Role;
use Repository\BaseRepository;

class RoleRepository extends BaseRepository {
    
    public function model()
    {
        return Role::class;
    }

    public function getAllRoleForAdmin()
    {
        return $this->model()::where('slug', '!=', 'super_admin')->get();
    }

    public function updateByID($id, array $modelData)
    {
        $model = $this->findOrFailByID($id);
        return $model->updateOrCreate($modelData);
    }

    public function deleteRole($id)
    {
        $role = $this->findByID($id);
        $role->delete(); 
    }
}
