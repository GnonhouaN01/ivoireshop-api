<?php

namespace App\Services;

use App\Interfaces\ImageServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService implements ImageServiceInterface
{
    /**
     * Upload and process image
     */
    public function uploadImage(UploadedFile $file, string $directory = 'images', array $sizes = []): array
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, 'public');

        $paths = ['original' => $path];

        // Generate resized versions
        foreach ($sizes as $sizeName => $dimensions) {
            $resizedPath = $this->resizeImage($file, $directory, $filename, $dimensions['width'], $dimensions['height'], $sizeName);
            $paths[$sizeName] = $resizedPath;
        }

        return $paths;
    }

    /**
     * Resize image
     */
    private function resizeImage(UploadedFile $file, string $directory, string $filename, int $width, int $height, string $suffix): string
    {
        $image = Image::make($file)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $resizedFilename = pathinfo($filename, PATHINFO_FILENAME) . "_{$suffix}." . pathinfo($filename, PATHINFO_EXTENSION);
        $resizedPath = "{$directory}/{$resizedFilename}";

        Storage::disk('public')->put($resizedPath, $image->encode());

        return $resizedPath;
    }

    /**
     * Delete image and its variants
     */
    public function deleteImage(string $path): bool
    {
        $directory = dirname($path);
        $filename = pathinfo($path, PATHINFO_FILENAME);
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        // Delete original
        Storage::disk('public')->delete($path);

        // Delete variants (assuming common suffixes)
        $variants = ['thumb', 'medium', 'large'];
        foreach ($variants as $variant) {
            $variantPath = "{$directory}/{$filename}_{$variant}.{$extension}";
            Storage::disk('public')->delete($variantPath);
        }

        return true;
    }

    /**
     * Get image URL
     */
    public function getImageUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }
}
