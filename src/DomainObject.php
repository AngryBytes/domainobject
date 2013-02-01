<?php
/**
 * DomainObject.php
 *
 * ABC Manager 5
 *
 * @category        Abc
 * @package         Core
 * @subpackage      Domain
 * @copyright       Copyright (c) 2010 Angry Bytes BV (http://www.angrybytes.com)
 */

namespace Abc\Core;

use \InvalidArgumentException as InvalidArgumentException;

// Reflection
use \ReflectionObject as ReflectionObject;
use \ReflectionMethod as ReflectionMethod;

/**
 * DomainObject
 *
 * This class signifies a Domain Object. A Domain Object contains values and
 * represents an instance of a modelled entity from the domain.
 *
 * When a class extends this it can implement getters and setters for values.
 * This is mainly a way to get around PHP's (< 5.5) lack of properties, but
 * there's also a strong signal given by extending it.
 *
 * Sample implementation:
 *
 * <code>
 * class Foo extends DomainObject {
 *
 *    private $bar;
 *
 *    public function getBar() {
 *         return $this->bar;
 *    }
 *
 *    public function setBar($bar) {
 *        $this->bar = $bar;
 *    }
 * }
 * </code>
 *
 * When extending this class the implementer writes a number of getters/setters
 * for every property. This class has overloaded `__get()` and `__set()`
 * methods, that allow for:
 *
 * <code>
 * $foo = new Foo;
 * $foo->bar = 'baz';
 *
 * // echoes 'baz'
 * echo $foo->bar;
 * </code>
 *
 * @category        Abc
 * @package         Core
 * @subpackage      Domain
 */
class DomainObject
{
    /**
     * Get all properties of this DomainObject
     *
     * Inflects all getter methods and retrieves the property name from them
     *
     * @return array[int]string
     **/
    public function getProperties()
    {
        $properties = array();
        foreach ($this->getGetters() as $getter) {
            $properties[] = self::propertyNameFromGetter($getter);
        }

        return $properties;
    }

    /**
     * Get the DO as an array
     *
     * Finds all properties contained in getter methods, and sets their values
     * in a hash.
     *
     * This method will recurse over child DomainObjects, however, there is no
     * guard around this, so recursive objects will result in an infinite loop
     *
     * @todo remove infinite recursion
     *
     * @see DomainObject::getProperties()
     *
     * @return array[string]mixed
     **/
    public function toArray()
    {
        $array = array();

        foreach ($this->getProperties() as $property) {
            $array[$property] = $this->getPropertyValueAsSimple($property);
        }

        return $array;
    }

    /**
     * Create an array of the DO with properties
     *
     * @param  array[int]string   $properties
     * @return array[string]mixed
     */
    public function toArrayWithProperties(array $properties)
    {
        $array = array();
        foreach ($properties as $property) {
            $array[$property] = $this->getPropertyValueAsSimple($property);
        }

        return $array;
    }

    /**
     * Get a property value as a simple
     *
     * This will use toArray() on all DomainObject properties, and recursively
     * do this for all traversable properties as well, turning them into arrays.
     *
     * @see toArray()
     *
     * @param  string       $property
     * @return array|scalar
     **/
    public function getPropertyValueAsSimple($property)
    {
        if ($this->$property instanceof DomainObject) {

            // Simple recursion for child DO's
            return $this->$property->toArray();

        } elseif ($this->propertyIsTraversable($property)) {
            // Property is traversable
            $value = array();

            // Traverse the
            foreach ($this->$property as $childKey => $childValue) {
                if ($childValue instanceof DomainObject) {
                    $value[$childKey] = $childValue->toArray();
                } else {
                    $value[$childKey] = $childValue;
                }
            }

            return $value;

        }

        // All other properties are returned as is
        return $this->$property;
    }

    /**
     * Is a property traversable?
     *
     * @param  string $property
     * @return bool
     */
    public function propertyIsTraversable($property)
    {
        return is_array($this->$property)
            || $this->$property instanceof \Traversable
            ||  $this->$property instanceof \stdClass
            ;
    }

    /**
     * Overloaded getter for access to properties without using getter method
     *
     * @param  string $name
     * @return mixed
     **/
    public function __get($name)
    {
        // Getter function name
        $function = self::getterNameFromProperty($name);

        // Make sure there's a getter
        if (!method_exists($this, $function)) {
            throw new InvalidArgumentException(
                'No getter for "' . $name . '" in "' . get_class($this) . '"'
            );
        }

        return $this->$function();
    }

    /**
     * Overloaded setter for access to properties without using setter method
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     **/
    public function __set($name, $value)
    {
        // Setter function name
        $function = self::setterNameFromProperty($name);

        // Make sure there's a getter
        if (!method_exists($this, $function)) {
            throw new InvalidArgumentException('No setter for "' . $name . '"');
        }

        return $this->$function($value);
    }

    /**
     * Overloaded isset
     *
     * Assumes "set" when there is a getter for a property
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        return method_exists(
            $this,
            self::getterNameFromProperty($name)
        );
    }

    /**
     * Is there a setter for $propertyName?
     *
     * @param string $propertyName
     * @return bool
     **/
    private function hasSetter($propertyName)
    {
        return method_exists(
            $this,
            self::setterNameFromProperty($propertyName)
        );
    }

    /**
     * Is there a getter for $propertyName?
     *
     * @param string $propertyName
     * @return bool
     **/
    private function hasGetter($propertyName)
    {
        return method_exists(
            $this,
            self::getterNameFromProperty($propertyName)
        );
    }

    /**
     * Get all getter methods of the class
     *
     * @return array[int]string
     **/
    private function getGetters()
    {
        // Reflect the instance
        $reflection = new ReflectionObject($this);

        $getters = array();

        // List all methods
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {

            // Only methods starting with "get" make the cut
            if (substr($method->getName(), 0, 3) !== 'get') {
                continue;
            }

            // The properties getter from DO core is skipped
            if ($method->getName() === 'getProperties') {
                continue;
            }

            // Static methods don't make the cut
            if ($method->isStatic()) {
                continue;
            }

            // Don't include getters that need params
            if (count($method->getParameters()) > 0) {
                continue;
            }

            // Add the property to the list
            $getters[] = $method->getName();
        }

        return $getters;
    }

    /**
     * Get the name of the getter function for a property name
     *
     * @param  string $name
     * @return string
     **/
    private static function getterNameFromProperty($name)
    {
        return 'get' . ucfirst($name);
    }

    /**
     * Get the name of the setter function for a property name
     *
     * @param  string $name
     * @return string
     **/
    private static function setterNameFromProperty($name)
    {
        return 'set' . ucfirst($name);
    }

    /**
     * Get the property name contained in a getter function name
     *
     * @param  string $getter
     * @return string
     **/
    private static function propertyNameFromGetter($getter)
    {
        return lcfirst(
            substr($getter, 3)
        );
    }
}
