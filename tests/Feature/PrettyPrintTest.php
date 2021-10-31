<?php

class PrettyPrintTest extends Archetype\Tests\TestCase
{	
    public function test_arrays_are_beutiful_when_loaded_and_rendered()
    {
		$output = LaravelFile::user()->render();
        $this->assertMultilineArray('fillable', $output);
    }

    public function test_arrays_are_beutiful_when_loaded_modified_and_rendered()
    {
		$this->markTestIncomplete();

		$output = LaravelFile::user()
			->add('also')->to()->property('fillable')
			->render();

        $this->assertMultilineArray('fillable', $output);
    }	

    public function test_arrays_are_beutiful_when_created_and_rendered()
    {
		$this->markTestIncomplete();

		$output = PHPFile::class('FillableClass')
			->add()->property('fillable', ['first', 'second', 'third'])
			->render();
        
		$this->assertMultilineArray('ints', $output);
    }
	
    public function test_arrays_are_beutiful_when_empty()
    {
		$this->markTestIncomplete();
    }	

    public function test_arrays_have_trailing_comma_after_last_item()
    {
		$this->markTestIncomplete();
    }		
}
