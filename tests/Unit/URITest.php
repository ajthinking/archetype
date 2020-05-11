<?php

namespace PHPFileManipulator\Tests\Unit;

use PHPFileManipulator\Tests\FileTestCase;

use LaravelFile;
use PHPFile;
use Str;
use TypeError;
use PHPFileManipulator\Support\URI\NameURI;
use PHPFileManipulator\Support\URI\PathURI;
use PHPFileManipulator\Support\URI\URIFactory;

class URITest extends FileTestCase
{
    /** @test */
    public function it_can_enterpret_input_as_path_or_name()
    {
        $inputOutputPairs = [
            // path
            ''              => PathURI::class,
            'car'           => PathURI::class,
            'car.php'       => PathURI::class,
            'Car.php'       => PathURI::class,
            'app/Car'       => PathURI::class,
            '/Car'          => PathURI::class,

            // name
            'Car'           => NameURI::class,            
            '\\Car'         => NameURI::class,
            'App\\Car'      => NameURI::class,
            '\\App\\Car'    => NameURI::class,
        ];

        foreach($inputOutputPairs as $input => $expected) {
            // How to chain this to get better output?
            
            $this->assertInstanceOf(
                $expected,
                URIFactory::make($input)
            ); 
        }
    }
}
