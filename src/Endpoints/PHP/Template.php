<?php

namespace PHPFileManipulator\Endpoints\PHP;

use PHPFileManipulator\Endpoints\EndpointProvider;

class Template extends EndpointProvider
{
    public function class($name)
    {
        $outputDriverClass = config('php-file-manipulator.output', \PHPFileManipulator\Drivers\FileOutput::class);
        $outputDriver = new $outputDriverClass;
        $outputDriver->filename = $name;
        $outputDriver->extension = 'php';
        $outputDriver->relativeDir = 'app';

        $file = $this->file->fromString('<?php' . PHP_EOL . 'class C {}');
        $file->outputDriver($outputDriver);

        return $file;
    }
}