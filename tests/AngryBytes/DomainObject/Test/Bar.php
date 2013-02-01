<?php
/**
 * Bar.php
 *
 * ABC Manager 5
 *
 * @category        AngryBytes
 * @package         DomainObject
 * @subpackage      Test
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace AngryBytes\DomainObject\Test;

use AngryBytes\DomainObject;

/**
 * Bar
 *
 * Bar object, holds a Foo
 *
 * @category        AngryBytes
 * @package         DomainObject
 * @subpackage      Test
 */
class Bar extends DomainObject
{
    /**
     * Foo prop
     *
     * @var Foo
     **/
    private $foo;

    /**
     * Bar
     *
     * @var string
     **/
    private $bar;

    /**
     * Constructor
     *
     * @return void
     **/
    public function __construct()
    {
        $foo = new Foo;

        $foo
            ->setFoo('foo')
            ->setBar('bar');

        $this
            ->setFoo($foo)
            ->setBar('bar');
    }

    /**
     * Get the foo property
     *
     * @return Foo
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * Set the foo property
     *
     * @param Foo $foo
     * @return Bar
     */
    public function setFoo(Foo $foo)
    {
        $this->foo = $foo;

        return $this;
    }

    /**
     * Get the bar prop
     *
     * @return string
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * Set the bar prop
     *
     * @param string $bar
     * @return Bar
     */
    public function setBar($bar)
    {
        $this->bar = $bar;

        return $this;
    }
}
