<?php

use Illuminate\Support\Facades\Route;

function isBase64Image($image) {
    if (($pos = strpos($image, ",")) !== FALSE) { 
        $image = substr($image, $pos+1); 
    }
    
    try {
        $image = imagecreatefromstring(base64_decode($image));
        imagepng($image, 'myimage.png');
        getimagesize('myimage.png');
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

function currentApiRouteName($name) {
    $apiVersion = config('app.api.version');
    return Route::currentRouteName() == "api.{$apiVersion}.{$name}";
}
