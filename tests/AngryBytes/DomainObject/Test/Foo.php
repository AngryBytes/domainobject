<?php

namespace AngryBytes\DomainObject\Test;

/**
 * Foo DomainObject
 */
class Foo extends \AngryBytes\DomainObject
{
    private string $foo;
    private string $bar;

    public function getFoo(): string
    {
        return $this->foo;
    }

    public function setFoo(string $foo): self
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
