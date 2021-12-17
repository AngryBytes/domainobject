<?php

namespace AngryBytes\DomainObject\Test;

/**
 * Testing composed object
 */
class ComposedTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test properties
     */
    public function testProperties(): void
    {
        $bar = new Bar();

        $this->assertEquals(
            'bar',
            $bar->foo->bar
        );

        $this->assertEquals(
            'bar',
            $bar->bar
        );
    }

    /**
     * Test composed to array
     */
    public function testToArray(): void
    {
        $bar = new Bar();

        $array = $bar->toArray();

        $this->assertArrayHasKey(
            'foo',
            $array
        );

        // Recursed
        $this->assertArrayHasKey(
            'foo',
            $array['foo']
        );
        $this->assertArrayHasKey(
            'bar',
            $array['foo']
        );

        $this->assertEquals(
            'bar',
            $array['foo']['bar']
        );
    }

    /**
     * Testing to array with property list
     */
    public function testToArrayWithProperties(): void
    {
        $bar = new Bar();

        $array = $bar->toArrayWithProperties(['bar']);

        $this->assertArrayHasKey(
            'bar',
            $array
        );

        $this->assertTrue(
            !isset($array['foo'])
        );
    }
}
