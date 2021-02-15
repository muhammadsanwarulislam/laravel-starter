<?php

namespace Repository\User;
use App\Models\Role;
use App\Models\User;
use App\Models\Profile;
use Repository\BaseRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserRepository extends BaseRepository {

    public function model()
    {
        return User::class;
    }

    public function allRole()
    {
        return Role::get();
    }

    public function findByUser($id)
    {
        return Profile::where('user_id', $id)->first();
    }

    public function updateProfileByID($id, array $modelData)
    {
        $profile = Profile::where('user_id', $id)->first();
         if($profile != null)
        {
            $profile->update($modelData);
        }else{
            return Profile::create($modelData);
        }
    }

    public function storeFile(UploadedFile $file)
    {
        return Storage::put('fileuploads/user', $file);
    }

    public function updateFile($id)
    {
        $userImage = Profile::where('user_id', $id)->first();
        if($userImage)
        {
            Storage::delete($userImage->image);
        }
    }

    public function updatePasswordByID($id)
    {
        $hashedPassword = Auth::user()->password;
        if (Hash::check($id['current_password'], $hashedPassword)) {
            if (!Hash::check($id['password'], $hashedPassword)) {
                Auth::user()->update([
                    'password' => Hash::make($id['password'])
                ]);
                Auth::logout();
                notify()->success('Password Successfully Changed.', 'Success');
                return redirect()->route('login');
            } else {
                notify()->warning('New password cannot be the same as old password.', 'Warning');
            }
        } else {
            notify()->error('Current password not match.', 'Error');
        }
    }

    public function deleteByID($id)
    {
        $userImage = $this->findByID($id);
        Storage::delete($userImage->image);
        $userImage->delete(); 
    }

    public function publishByID($id)
    {
        try {
            $publish = $this->findByID($id);
            if ($publish->status === 1) {
                $publish->status = 0;
                $message = 'User Publish Successfully';
            } else {
                $publish->status = 1;
                $message = 'User Unpublish Successfully';
            }
            $publish->save();
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
        }
        notify()->success($message);
    }

    public function blockByID($id)
    {
        try {
            $blocked = $this->findByID($id);
            if ($blocked->suspend === 1) {
                $blocked->suspend = 0;
                $message = 'User Blocked Successfully';
            } else {
                $blocked->suspend = 1;
                $message = 'User Unblocked Successfully';
            }
            $blocked->save();
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
        }
        notify()->success($message); 
    }

}
