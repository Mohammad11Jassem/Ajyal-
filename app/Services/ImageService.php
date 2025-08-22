<?php

namespace App\Services;

use App\Models\Image;
use Exception;
use Maatwebsite\Excel\Excel;

class ImageService
{
    public function deleteImage($imageID)
    {
        try{
            Image::findOrFail($imageID)->delete();
            return [
                'success'=>true,
                'message'=>'تم مسح الصورة'
            ];
        }catch(Exception $e){
            return[
                'success'=>false,
                'message'=>'فشلت عملية مسح الصورة'
            ];
        }
    }
}
