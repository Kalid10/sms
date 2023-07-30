<?php

namespace App\Services;

use Intervention\Image\Facades\Image;

class ImageService
{
    public function resizeImage($filePath, $width, $height): \Intervention\Image\Image
    {
        // Create an instance from the file located at $filePath
        $img = Image::make($filePath);

        // Resize the image to a width of $width and constrain aspect ratio
        // This will auto-adjust the height if necessary
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        // You may save the resized image back to disk if needed
        // Make sure the destination directory exists
        $img->save(public_path('resized_images/'.basename($filePath)));

        return $img;
    }
}
