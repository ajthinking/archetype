<?php

use Archetype\Schema\SimpleSchema\Attribute;
use Archetype\Schema\SimpleSchema\SimpleSchema;
use Archetype\Schema\SimpleSchema\SimpleSchemaParser;

require('samples.php');

class SimpleSchemaTest extends Archetype\Tests\TestCase
{
    /** @test */
    public function it_can_read_from_a_string_with_a_parse_method()
    {
        $parser = SimpleSchema::parse(THREE_MODELS_WITH_ATTRIBUTES);

        // after ::parse($text)
        $this->assertInstanceOf(SimpleSchemaParser::class, $parser);
        // run ->get()
        $this->assertInstanceOf(SimpleSchema::class, $parser->get());
    }

    /** @test */
    public function it_will_clean_up_whitespace()
    {
        $parser1 = SimpleSchema::parse(THREE_MODELS_WITH_ATTRIBUTES);


        $parser2 = SimpleSchema::parse(THREE_MODELS_WITH_ATTRIBUTES_DIRTY);

        $this->assertNotEquals(
            $parser1->original,
            $parser2->original
        );
        
        $this->assertEquals(
            $parser1->cleaned,
            $parser2->cleaned
        );        
        
    }     
    
    /** @test */
    public function the_schema_has_entities()
    {
        $schema = SimpleSchema::parse(THREE_MODELS_WITH_ATTRIBUTES)->get();        

        $this->assertCount(3, $schema->entities);

        $this->assertEquals(
            collect(['Model1','Model2','Model3']),
            collect($schema->entities)->map->name,    
        );

        $this->assertEquals(
            new Attribute('attribute1', collect()),
            collect($schema->entities)->first()->attributes->first(),  
        );
    }
    
    /** @test */
    public function entities_can_have_directives()
    {
        $schema = SimpleSchema::parse(ENTITY_WITH_ATTRIBUTE_DIRECTIVES)->get();

        $directives = $schema->entities->first()->attributes->first()->directives;

        // float:8,2
        $this->assertEquals('float', $directives->first()->name);
        $this->assertEquals(collect([8,2]), $directives->first()->arguments);

        // fillable
        $this->assertEquals('fillable', $directives->last()->name);
        $this->assertEquals(collect(), $directives->last()->arguments);        
    }    
}