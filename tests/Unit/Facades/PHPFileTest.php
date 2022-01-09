<?php

use Archetype\Support\Exceptions\FileParseError;
use Archetype\Tests\Support\Facades\TestablePHPFile as PHPFile;
use Illuminate\Support\Facades\Config;
use PhpParser\Node\Stmt\InlineHTML;

use function PHPUnit\Framework\assertInstanceOf;

describe('#load', function() {
	it('can load files inside default root using a relative path', function() {
		PHPFile::load('public/index.php')
			->assertValidPhp();
	});

	it('can load files inside default root using an absolute path', function() {
		PHPFile::load(
			base_path('app/Models/User.php')
		)->assertValidPhp();
	});
	
	it('can load files outside default root using an absolute path', function() {
		PHPFile::load(
			__DIR__."/../../TestCase.php"
		)->assertValidPhp();
	});
	
	it('can load using namespaced class', function() {
		$this->markTestIncomplete();

		PHPFile::load(App\User::class)
			->assertValidPhp();
	});	
});

describe('#save', function() {
	it('can write to default location', function() {
		PHPFile::load('app/Models/User.php')->save();
		$this->assertTrue(is_file(Config::get('archetype.roots.output.root') . '/app/Models/User.php'));
	});

	it('can write to a debug location', function() {
		PHPFile::load('app/Models/User.php')->debug();
		$this->assertTrue(is_file(Config::get('archetype.roots.debug.root') . '/app/Models/User.php'));
	});	
});

describe('#directive', function() {
	it('can set and get arbitrary directives', function() {
		PHPFile::directive('isCool', true)
			->assertDirective('isCool', true);
	});

	it('can attempt get on uninitialized arbitrary directives', function() {
		PHPFile::assertDirective('something-missing', null);
	});

	it('can be set using helper methods', function() {
		PHPFile::add()
			->remove()
			->clear()
			->empty()
			->addMissingTags(false)
			->full()
			->public()
			->protected()
			->private()
			->assumeType('array')
			->assertDirectives([
				'add' => true,
				'remove' => true,
				'clear' => true,
				'empty'	 => true,
				'addMissingTags' => false,
				'full' => true,
				'flag' => 'private',
				'assumeType' => 'array',
			]);
	});
});

describe('#fromString', function() {
	it('can instanciate using a php string', function() {
		PHPFile::fromString('<?php 1337;')
			->assertValidPhp();
	});

	it('allows missing php tag and end semicolon when testing', function() {
		PHPFile::fromString('1337')
			->assertValidPhp();
	});	

	it('will interpret content as html if opening php tag is missing', function() {
		assertInstanceOf(
			InlineHTML::class,
			\Archetype\Facades\PHPFile::fromString('1337;')->ast()[0]
		);
	});

	it('will throw error if php code cant be parsed', function() {
		$this->expectException(FileParseError::class);
	
		PHPFile::fromString('<?php ¯\_(ツ)_/¯;');
	});

	it('assumes code is php and adds starting tag and missing end semicolon when directive addMissingTags is used', function() {
		PHPFile::addMissingTags(true)->fromString('1337')
			->assertValidPhp();
	});
});

describe('#__call', function() {
	it('catches non existing methods', function () {
		PHPFile::this_method_does_not_exists();
	})->throws(BadMethodCallException::class);

	it('can delegate method calls to resources', function () {
		PHPFile::make()->class()->assertInstanceOf(
			\Archetype\Tests\Support\TestablePHPFile::class
		);
	});	
});