<?php

namespace PHPFileManipulator\Support;

use Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PHPFileStorage
{
    public static function get($path)
    {
        $disk = static::getStorageDisk('input');
        $pathPrefix = $disk->getDriver()->getAdapter()->getPathPrefix();
        $result =  $disk->get(
            static::relativeInputPath($path)
        );

        return $result;
    }

    public static function put($path, $content)
    {
        return static::getStorageDisk('output')->put(
            static::relativeOutputPath($path),
            $content
        );
    }    

    public static function relativeInputPath($path)
    {
        return static::relativePathFor('input', $path);
    }

    public static function fullInputPath($path)
    {
        return static::fullPathFor('input', $path);
    }

    public static function relativeOutputPath($path)
    {
        return static::relativePathFor('output', $path);
    }

    public static function fullOutputPath($path)
    {
        return static::fullPathFor('output', $path);
    }
    
    private static function relativePathFor($type, $path)
    {
        return Str::replaceFirst(
            static::getStorageRootPath($type) . '/',
            '',
            $path
        );
    }

    public static function fullPathFor($type, $path)
    {
        return Str::startsWith($path, '/') ? $path 
        : static::getStorageRootPath($type) . "/$path";        
    }    

    private static function getStorageRootPath($name)
    {
        return Config::get("php-file-manipulator.roots.$name.root");
    }
    
    private static function getStorageDisk($name)
    {        
        $disk = Config::get("php-file-manipulator.roots.$name");
        
        Config::set("filesystems.disks.roots.$name", $disk);

        return Storage::disk("roots.$name");
    }    
}