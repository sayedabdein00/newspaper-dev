<?php

namespace App\Traits;
use Intervention\Image\Image;

trait ProfileImage{
    public function UploadImage($request, $model, $storage_location="public/uploads/profile_images/")
    {
        //check it image uploaeded
        if($request->hasFile('images')){

            //check if already exists previous image
            if($model->images != null){
                // delete old photo
                $old_photo_path = $storage_location.$model->images;
                unlink(base_path($old_photo_path));
            }

            $uploaded_photo = $request->file('images');
            $new_photo_name = $model->id.'.'.$uploaded_photo->getClientOriginalExtension(); // 1.jpg
            $new_photo_location = $storage_location.$new_photo_name; //public/uploads/profile_images/1.jpg

            // Save image
            Image::make($uploaded_photo)->resize(600,600)->save(base_path($new_photo_location));

            $model->update([
                'images' => $new_photo_name,
            ]);

        }
    }
}

