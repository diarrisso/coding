<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Teaser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function uploadTeaserImage(UploadedFile $file, Teaser $teaser): string
    {
        $imageName = Str::uuid() . '.' . $file->getClientOriginalExtension();

        $path = 'teasers/' . $teaser->id;

        Storage::disk('public')->makeDirectory($path);

        $manager = new ImageManager(new Driver());

        $img = $manager->read($file->path());

        $width = 1200;
        $height = round($width / 16 * 9);

        $img = $img->cover($width, $height);

        $imagePath = $path . '/' . $imageName;
        $encodedImage = $img->toJpeg();

        Storage::disk('public')->put($imagePath, $encodedImage);

        $teaser->update(['image_name' => $imageName]);

        return $imageName;
    }

    /**
     * Supprime l'image d'un teaser.
     */
    public function removeTeaserImage(Teaser $teaser): bool
    {
        if ($teaser->image_name) {
            $path = 'teasers/' . $teaser->id . '/' . $teaser->image_name;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                $teaser->update(['image_name' => null]);
                return true;
            }
        }
        return false;
    }
}
