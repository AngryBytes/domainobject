<?php
/**
 * ComposedTest.php
 *
 * ABC Manager 5
 *
 * @category        Abc
 * @package         DomainObject
 * @subpackage      Test
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\DomainObject\Test;

/**
 * ComposedTest
 *
 * Testing composed object
 *
 * @category        Abc
 * @package         DomainObject
 * @subpackage      Test
 */
class ComposedTest extends TestCase
{

    /**
     * Test properties
     *
     * @return void
     **/
    public function testProperties()
    {
        $bar = $this->createBar();

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
     *
     * @return void
     **/
    public function testToArray()
    {
        $bar = $this->createBar();

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
     *
     * @return void
     **/
    public function testToArrayWithProperties()
    {
        $bar = $this->createBar();

        $array = $bar->toArrayWithProperties(array('bar'));

        $this->assertArrayHasKey(
            'bar',
            $array
        );

        $this->assertTrue(
            !isset($array['foo'])
        );

    }

    /**
     * Create a Bar
     *
     * @return Bar
     **/
    private function createBar()
    {
        return new Bar;
    }
}
