<?php

namespace PHPFileManipulator\Support;

use Config;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use PHPFileManipulator\Support\Path;

class PHPFileStorage
{
    public function __construct()
    {
        $this->roots = config('php-file-manipulator.roots');
    }

    public function get($path)
    {
        return $this->getStorageDisk('input')->get(
            $this->relative($path, 'input')
        );
    }

    public function put($path, $content)
    {
        return $this->getStorageDisk('output')->put(
            $this->relative($path, 'output'),
            $content
        );
    }    

    public function relative($path, $rootName)
    {
        $root = Path::make($this->roots[$rootName]['root'])->full();

        return Path::make($path)->relative($root);
    }

    public function fullPathFor($type, $path)
    {
        return Str::startsWith($path, '/') ? $path 
        : static::getStorageRootPath($type) . "/$path";        
    }    

    protected function getStorageRootPath($name)
    {
        return Config::get("php-file-manipulator.roots.$name.root");
    }
    
    protected function getStorageDisk($name)
    {
        $root = $this->roots[$name]['root'];
        $unique_id = Str::slug($root);

        // Laravel config is imutable, so use the actual root as the key
        Config::set("filesystems.disks.roots.$unique_id", [
            'driver' => 'local',
            'root' => $root,
        ]);

        return Storage::disk("roots.$unique_id");
    }    
}