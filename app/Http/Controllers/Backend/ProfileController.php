<?php

namespace App\Http\Controllers\Backend;

use Image;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Requests\ProfilePasswordChangeRequest;

class ProfileController extends Controller
{
    public function getUpdateProfile()
    {
        $authuser = Auth::user();
        return view('admin.pages.profile.update-profile', compact('authuser'));
    }

    public function updateProfile(ProfileStoreRequest $request)
    {
        // dd($request->all());
        $user = User::whereEmail($request->email)->first();
        $this->image_upload($request, $user->id);

        return back();
    }

    public function getUpdatePassword()
    {
        return view('admin.pages.profile.update-password');
    }

    public function updatePassword(ProfilePasswordChangeRequest $request)
    {
        $user = Auth::user();
        $hashedPassword = $user->password;

        // existing password === request password
        if(Hash::check($request->old_password, $hashedPassword)){

            // new password == old stored passowrd
            if(!Hash::check($request->password, $hashedPassword)){
                $user->update([
                    'password' => Hash::make($request->password),
                ]);

            Auth::logout();
            return redirect()->route('login');
            }else{
                return back();
            }
        }else{
            return back();
        }
    }


    public function image_upload($request, $user_id)
    {
        //check it image uploaeded
        if($request->hasFile('user_image')){
            $user = User::find($user_id);

            //check if already exists previous image
            if($user->user_image != null){
                // delete old photo
                $old_photo_path = 'public/uploads/profile_images/'.$user->user_image;
                unlink(base_path($old_photo_path));
            }


            $photo_location = 'public/uploads/profile_images/';
            $uploaded_photo = $request->file('user_image');
            $new_photo_name = $user_id.'.'.$uploaded_photo->getClientOriginalExtension(); // 1.jpg
            $new_photo_location = $photo_location.$new_photo_name; //public/uploads/profile_images/1.jpg

            // Save image
            Image::make($uploaded_photo)->resize(600,600)->save(base_path($new_photo_location));

            $user->update([
                'user_image' => $new_photo_name,
            ]);

        }
    }
}
