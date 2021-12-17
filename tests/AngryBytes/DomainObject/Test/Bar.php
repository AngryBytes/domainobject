<?php

namespace AngryBytes\DomainObject\Test;

/**
 * Bar object, holds a Foo
 */
class Bar extends \AngryBytes\DomainObject
{
    private Foo $foo;
    private string $bar;

    public function __construct()
    {
        $foo = new Foo();

        $foo
            ->setFoo('foo')
            ->setBar('bar');

        $this
            ->setFoo($foo)
            ->setBar('bar');
    }

    public function getFoo(): Foo
    {
        return $this->foo;
    }

    public function setFoo(Foo $foo): self
    {
        $this->foo = $foo;

        return $this;
    }

    public function getBar(): string
    {
        return $this->bar;
    }

    public function setBar(string $bar): self
    {
        $this->bar = $bar;

        return $this;
    }
}
