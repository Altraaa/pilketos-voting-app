<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadImageService
{
    /**
     *
     * @param UploadedFile $file
     * @return string $path
     */
    public function upload(UploadedFile $file): string
    {
        $path = $file->store('candidates', 'public');

        return $path; 
    }

    /**
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}