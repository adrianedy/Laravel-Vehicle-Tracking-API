<?php

namespace App\Traits;

use Image;
use Carbon\Carbon;

trait ImageHandling
{
    public function storeCroppedImage($image, $folder, $size, $crop, $name = null)
    {
        if (is_file($image)) {
            $ext = $image->getClientOriginalExtension();
            $image = $image->getRealPath();
        } else {
            try {
                $ext = explode('/', mime_content_type($image))[1];
            } catch(\Exception $e) {
                $ext = 'jpg';
            }
            $image = base64_decode($image);
        }
        
        if (!file_exists(public_path('storage/' . $folder))) {
            mkdir(public_path('storage/' . $folder), 777, true);
        }

        $name   = $name ?? $this->imageNameGenerator($ext);
        $store  = Image::make($image)
                ->crop(round($crop['width']), round($crop['height']), round($crop['x']), round($crop['y']))
                ->resize($size[0], $size[1])
                ->save(public_path('storage/' . $folder) . $name);

        if ($store) {  
            return $name;
        } else {
            return false;
        }
    }

    public function storeImage($image, $folder, $name = null)
    {
        if (is_file($image)) {
            $ext = $image->getClientOriginalExtension();
            $image = $image->getRealPath();
        } else {
            try {
                $ext = explode('/', mime_content_type($image))[1];
                $image = substr($image, strpos($image, ",")+1);
            } catch(\Exception $e) {
                $ext = 'jpg';
            }
            $image = base64_decode($image);
        }
        
        if (!file_exists(public_path('storage/' . $folder))) {
            mkdir(public_path('storage/' . $folder), 755, true);
        }

        $name   = $name ?? $this->imageNameGenerator($ext);
        $store  = Image::make($image)->save(public_path('storage/' . $folder) . $name);

        if ($store) {  
            return $name;
        } else {
            return false;
        }
    }

    public function imageNameGenerator($extension = 'jpg')
    {
        return uniqid('img_') . strtotime(Carbon::now()) . '.' . $extension;
    }
}
