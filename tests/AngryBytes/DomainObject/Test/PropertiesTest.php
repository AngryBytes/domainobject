<?php
/**
 * PropertiesTest.php
 *
 * ABC Manager 5
 *
 * @category        AngryBytes
 * @package         DomainObject
 * @subpackage      Test
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\DomainObject\Test;

/**
 * PropertiesTest
 *
 * Testing properties
 *
 * @category        AngryBytes
 * @package         DomainObject
 * @subpackage      Test
 */
class PropertiesTest extends TestCase
{

    /**
     * Test properties access
     *
     * @return void
     **/
    public function testProperties()
    {
        $foo = $this->createFoo();

        $this->assertEquals(
            'foo',
            $foo->getFoo()
        );
        $this->assertEquals(
            'foo',
            $foo->foo
        );

        $this->assertEquals(
            'bar',
            $foo->getBar()
        );
        $this->assertEquals(
            'bar',
            $foo->bar
        );
    }

    /**
     * Test array conversion
     *
     * @return void
     **/
    public function testArray()
    {
        $foo = $this->createFoo();

        $array = $foo->toArray();

        $this->arrayHasKey(
            'foo',
            $array
        );
        $this->arrayHasKey(
            'bar',
            $array
        );

        $this->assertEquals(
            'foo',
            $array['foo']
        );
        $this->assertEquals(
            'bar',
            $array['bar']
        );
    }

    /**
     * Create a Foo
     *
     * @return Foo
     **/
    private function createFoo()
    {
        $foo = new Foo;

        return $foo
            ->setFoo('foo')
            ->setBar('bar');
    }
}
