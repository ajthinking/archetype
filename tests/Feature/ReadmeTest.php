<?php

class ReadmeTest extends Archetype\Tests\FileTestCase
{
    /**
     * Example from: https://github.com/ajthinking/archetype#laravelfile-readwrite-api
     *
     * @test
     */
    public function it_can_build_files()
    {
        $file = LaravelFile::user()
            ->add()->use(['App\Traits\Dumpable', 'App\Contracts\PlayerInterface'])
            ->add()->implements('PlayerInterface')
            ->add()->trait('Dumpable')
            ->table('gdpr_users')
            ->add()->fillable('nickname')
            ->remove()->hidden()
            ->empty()->casts()
            ->hasMany('App\Game')
            ->belongsTo('App\Guild');

        // The $file has changed
        $this->assertNotEquals(
            LaravelFile::user()->render(),
            $file->render(),
        );

        // The produced code is valid - it can be reparsed into a new LaravelFile instance
        $recreatedFile = LaravelFile::fromString(
            $file->render()
        );

        // The original and recreated files will render identical code 
        $this->assertEquals(
            $file->render(),
            $recreatedFile->render()
        );

        // The file instances are not using the same references
        // i.e we can change ast of one and expect a diff
        $this->assertNotEquals(
            $file->className('NewName')->render(),
            $recreatedFile->render()
        );        
    }
}
