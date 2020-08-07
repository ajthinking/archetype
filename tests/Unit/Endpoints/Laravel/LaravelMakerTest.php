<?php

class LaravelMakerTest extends Archetype\Tests\FileTestCase
{
    /** @test
     * @group only
    */
    public function the_php_file_maker_defaults_to_root()
    {
        $this->markTestIncomplete();

        $output = LaravelFile::make()->model('Car')->outputDriver();
        
        $this->assertEquals($output->relativeDir, 'app');
        $this->assertEquals($output->filename, 'Car');
        $this->assertEquals($output->extension, 'php');
    }
}
