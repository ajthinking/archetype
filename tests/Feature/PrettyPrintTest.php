<?php

use Archetype\Facades\LaravelFile;
use Archetype\Facades\PHPFile;

class PrettyPrintTest extends Archetype\Tests\TestCase
{	
    public function test_arrays_are_beutiful_when_loaded_and_rendered()
    {
		$output = LaravelFile::user()->render();
        $this->assertMultilineArray('fillable', $output);
    }

    public function test_arrays_are_beutiful_when_loaded_modified_and_rendered()
    {
		$output = LaravelFile::user()
			->add('also')->to()->property('fillable')
			->render();

        $this->assertMultilineArray('fillable', $output);
    }	

    public function test_arrays_are_beautiful_when_created_and_rendered()
    {
		$output = PHPFile::class('FillableClass')
			->add()->property('fillable', ['first', 'second', 'third'])
			->render();

		$this->assertMultilineArray('fillable', $output);
    }
	
    public function test_arrays_are_beutiful_when_empty()
    {
		$output = PHPFile::class('FillableClass')
			->property('fillable', [])
			->render();
		
		$this->assertSingleLineEmptyArray('fillable', $output);
    }		
}
