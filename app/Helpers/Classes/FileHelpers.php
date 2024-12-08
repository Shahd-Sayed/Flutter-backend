<?php

namespace App\Helpers\Classes;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class FileHelpers
{

    public static function uploadFile($file, string $uploadTo, ?string $newDisk = null, ?string $oldPath = null, ?string $oldDisk = null)
    {
        $newDisk ??= config('filesystems.default');
        $oldDisk ??= config('filesystems.default');

        if ($file->store($uploadTo, $newDisk)):
            if ($oldPath && $oldDisk && $oldPath != $uploadTo):
                Storage::disk($oldDisk)->delete($oldPath);
            endif;
        else:
            throw new \Exception('Failed to upload file');
        endif;

        return $uploadTo;
    }

    public static function uploadImage($file, string $uploadTo, ?string $newDisk = null, ?string $oldPath = null, ?string $oldDisk = null, int $quality = 20, ?int $width = null, ?int $height = null)
    {
        $newDisk ??= config('filesystems.default');
        $oldDisk ??= config('filesystems.default');

        $image = Image::read($file->getRealPath())
            ->scale($width, $height)
            ->encodeByPath($uploadTo, quality: $quality);

        if (Storage::disk($newDisk)->put($uploadTo, $image)):
            if ($oldPath && $oldDisk && $oldPath != $uploadTo):
                Storage::disk($oldDisk)->delete($oldPath);
            endif;
        else:
            throw new \Exception('Failed to upload image');
        endif;

        return $uploadTo;
    }

    public static function getFileUrl(string $path, ?string $disk = null, $default = null, $expiration = null)
    {
        $disk ??= config('filesystems.default');

        if ($disk):
            $mustGetTempUrl = config('filesystems.disks.' . $disk . '.must_get_temp_url');
            if ($mustGetTempUrl):
                return Storage::disk($disk)->temporaryUrl($path, $expiration ?? now()->addMinutes(5));
            else:
                return Storage::disk($disk)->url($path);
            endif;
        else:
            return $default ?? $path;
        endif;
    }

    public static function deleteFile(string $path, ?string $disk = null)
    {
        if ($disk):
            return Storage::disk($disk)->delete($path);
        else:
            return Storage::delete($path);
        endif;
    }
}
