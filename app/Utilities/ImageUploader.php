<?php

namespace App\Utilities ;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageUploader {

    private const Delimiter = '_' ; 

    public static function upload ($file,$path,$diskType )
    {
        // $fullPath = $path . $file . static::Delimiter . $file->getClientOriginalName();
        Storage::disk($diskType)->put($path, File::get($file));
    }


    public static function multiUploader(array $files,$path,$diskType='public_storage' )
    {
        $imagePath = [];
        foreach ($files as $key => $file) :

            $fullPath = $path . $key . static::Delimiter . $file->getClientOriginalName();
            self::upload($fullPath,$file,$diskType);
            $imagePath += [$key => $fullPath] ;
        endforeach ;

        return $imagePath ;
    }

    

}