<?php

namespace AngryBytes\DomainObject\Test;

/**
 * Testing properties
 */
class PropertiesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test properties access
     */
    public function testProperties(): void
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
     */
    public function testArray(): void
    {
        $foo = $this->createFoo();

        $array = $foo->toArray();

        $this->arrayHasKey('foo');
        $this->arrayHasKey('bar');

        $this->assertEquals(
            'foo',
            $array['foo']
        );
        $this->assertEquals(
            'bar',
            $array['bar']
        );
    }

    private function createFoo(): Foo
    {
        $foo = new Foo();

        return $foo
            ->setFoo('foo')
            ->setBar('bar');
    }
}
