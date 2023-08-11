<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService
{
    public static function resize($filePath, $width, $height): \Intervention\Image\Image
    {
        // Create an instance from the file located at $filePath
        $img = Image::make($filePath);

        // Resize the image to a width of $width and constrain aspect ratio
        // This will auto-adjust the height if necessary
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        return $img;
    }

    public static function upload($image, $name, $visibility = 'public'): void
    {
        // Use Storage to put the file on Spaces
        Storage::disk('spaces')->put('rigel/profile-images/'.$name, $image->encode(), $visibility);
    }
}
