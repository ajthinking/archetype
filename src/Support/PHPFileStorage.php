<?php

namespace Archetype\Support;

use Archetype\Support\Path;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PHPFileStorage
{
    public function __construct()
    {
        $this->roots = config('archetype.roots');
    }

    public function get($path)
    {
        return $this->getStorageDisk('input')->get(
            $this->relative($path, 'input')
        );
    }

    public function put($path, $content)
    {
        $r = $this->getStorageDisk('output')->put(
            $this->relative($path, 'output'),
            $content
        );

        // dd(
        //     //$this->getStorageDisk('output')->getDriver()->pathPrefix,
        //     $this->getStorageDisk('output')->getDriver()->getAdapter()->getPathPrefix(),
        //     $this->relative($path, 'output'),
        //     is_file(
        //         $this->getStorageDisk('output')->getDriver()->getAdapter()->getPathPrefix() .
        //         $this->relative($path, 'output')
        //     )
        // );

        return $r;
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
        return Config::get("archetype.roots.$name.root");
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
