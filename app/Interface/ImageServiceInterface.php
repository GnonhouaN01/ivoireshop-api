<?php

namespace App\Interfaces;

use Illuminate\Http\UploadedFile;

interface ImageServiceInterface
{
    public function uploadImage(UploadedFile $file, string $directory = 'images', array $sizes = []): array;
    public function deleteImage(string $path): bool;
    public function getImageUrl(string $path): string;
}
