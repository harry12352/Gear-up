<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorage
{
    public static $disk = "public";

    public static function store($file, $folder)
    {
        $fileOriginal = $file->getClientOriginalName();

        $fileOnlyName = Str::slug(pathinfo($fileOriginal, PATHINFO_FILENAME));
        $fileExtension = pathinfo($fileOriginal, PATHINFO_EXTENSION);
        $fileName = $fileOnlyName . '.' . $fileExtension;

        $filePath = $folder . '/' . $fileName;

        if (self::checkFileExists($filePath)) {
            $fileName = self::generateUniqueFileName($fileName);
        }
        return $file->storeAs($folder, $fileName, self::$disk);
    }

    protected static function checkFileExists($filePath)
    {
        return Storage::disk(self::$disk)->exists($filePath);
    }

    protected static function generateUniqueFileName($imageName)
    {
        $uniqueString = md5(uniqid(rand(), true));
        return substr($uniqueString, 0, 6) . "_" . $imageName;
    }

    public static function delete($imagePath)
    {
        if (Storage::disk(self::$disk)->exists($imagePath)) {
            return Storage::disk(self::$disk)->delete($imagePath);
        } else {
            return true;
        }
    }

    public static function getUrl($filePath)
    {
        if (Storage::disk(self::$disk)->exists($filePath)) {
            return Storage::disk(self::$disk)->url($filePath);
        }
    }
}
