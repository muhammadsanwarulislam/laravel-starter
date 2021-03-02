<?php

namespace App\Http\Controllers\Backend\UserManagement;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Repository\Role\RoleRepository;
use Repository\User\UserRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Notifications\RegisteredUserConfirmationMail;

class SuperAdminController extends Controller
{
    protected $superAdminRepo;
    protected $roleRepo;
    
    public function __construct(
        UserRepository $superAdminRepository, 
        RoleRepository $roleRepository)
    {
        $this->superAdminRepo   =  $superAdminRepository;
        $this->roleRepo         =  $roleRepository;
    }

    public function index()
    {
        Gate::authorize('backend.super-admin.index');
        $users      = $this->superAdminRepo->getAll();
        return view('backend.user_management.super_admin.index',compact('users'));
    }

    public function create()
    {
        Gate::authorize('backend.super-admin.create');
        $roles = $this->roleRepo->getAll();
        return view('backend.user_management.super_admin.form', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        Gate::authorize('backend.super-admin.create');
        $user = DB::transaction(function () use ($request){
            $user = $this->superAdminRepo->create($request->except('role_id') + [
                'role_id'   =>  $request->role
            ]);
            $this->superAdminRepo->updateOrNewBy($user);
            Notification::send($user, new RegisteredUserConfirmationMail($user));
        });
        notify()->success('User Successfully Added.', 'Added');
        return redirect()->route('backend.super-admin.index');
    }

    public function show($id)
    {
        $user = $this->superAdminRepo->findByID($id);
        return view('backend.user_management.super_admin.show',compact('user'));
    }

    public function edit($id)
    {
        Gate::authorize('backend.super-admin.edit');
        $roles  = $this->roleRepo->getAll();
        $user   = $this->superAdminRepo->findByID($id);
        return view('backend.user_management.super_admin.form', compact('roles','user'));
    }

    public function update($id, UpdateUserRequest $request)
    {
        Gate::authorize('backend.super-admin.edit');
        $user  = $this->superAdminRepo->findByID($id);
        $user  = $this->superAdminRepo->updateByID($id,$request->except('role_id') + [
            'role_id'   =>  $request->role
        ]);
        notify()->success('User Successfully Updated.', 'Updated');
        return redirect()->route('backend.super-admin.index');
    }

    public function destroy($id)
    {
        Gate::authorize('backend.super-admin.destroy');
        $users = $this->superAdminRepo->deleteByID($id);
        notify()->success("User Successfully Deleted", "Deleted");
        return back();
    }

    public function togglePublish($id)
    {
        $publish = $this->superAdminRepo->publishByID($id);
        return back();
    }
    public function toggleBlock($id)
    {
        $block = $this->superAdminRepo->blockByID($id);
        return back();
    }
}
