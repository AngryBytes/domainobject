<?php
/**
 * Foo.php
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
 * Foo
 *
 * Foo DomainObject
 *
 * @category        AngryBytes
 * @package         DomainObject
 * @subpackage      Test
 */
class Foo extends DomainObject
{
    /**
     * Foo
     *
     * @var string
     **/
    private $foo;

    /**
     * Bar
     *
     * @var string
     **/
    private $bar;

    /**
     * Get the foo property
     *
     * @return string
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * Set the foo property
     *
     * @param string $foo
     * @return Foo
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;

        return $this;
    }

    /**
     * Get the bar property
     *
     * @return string
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * Set the bar property
     *
     * @param string $bar
     * @return Foo
     */
    public function setBar($bar)
    {
        $this->bar = $bar;

        return $this;
    }
}
