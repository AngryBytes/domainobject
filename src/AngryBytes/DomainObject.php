<?php

namespace AngryBytes;

use InvalidArgumentException;

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
 */
class DomainObject
{
    /**
     * Get all properties of this DomainObject
     *
     * Inflects all getter methods and retrieves the property name from them
     *
     * @return string[]
     */
    public function getProperties(): array
    {
        $properties = [];
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
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = [];

        foreach ($this->getProperties() as $property) {
            $array[$property] = $this->getPropertyValueAsSimple($property);
        }

        return $array;
    }

    /**
     * Create an array of the DO with properties
     *
     * @param  string[]             $properties
     * @return array<string, mixed>
     */
    public function toArrayWithProperties(array $properties): array
    {
        $array = [];
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
     * @return mixed
     */
    public function getPropertyValueAsSimple(string $property)
    {
        if ($this->$property instanceof DomainObject) {
            // Simple recursion for child DO's
            return $this->$property->toArray();
        }

        if ($this->propertyIsTraversable($property)) {
            // Property is traversable
            $value = [];

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
     */
    public function propertyIsTraversable(string $property): bool
    {
        return is_iterable($this->$property) || $this->$property instanceof \stdClass;
    }

    /**
     * Overloaded getter for access to properties without using getter method
     *
     * @return mixed
     */
    public function __get(string $name)
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
     */
    public function __set(string $name, mixed $value): void
    {
        // Setter function name
        $function = self::setterNameFromProperty($name);

        // Make sure there's a getter
        if (!method_exists($this, $function)) {
            throw new InvalidArgumentException('No setter for "' . $name . '"');
        }

        $this->$function($value);
    }

    /**
     * Overloaded isset
     *
     * Assumes "set" when there is a getter for a property
     */
    public function __isset(string $name): bool
    {
        return method_exists(
            $this,
            self::getterNameFromProperty($name)
        );
    }

    /**
     * Get all getter methods of the class
     *
     * @return string[]
     */
    private function getGetters(): array
    {
        // Reflect the instance
        $reflection = new \ReflectionObject($this);

        // List all methods
        $getters = [];
        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            // Only methods starting with "get" make the cut
            if (!str_starts_with($method->getName(), 'get')) {
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
     */
    private static function getterNameFromProperty(string $name): string
    {
        return 'get' . ucfirst($name);
    }

    /**
     * Get the name of the setter function for a property name
     */
    private static function setterNameFromProperty(string $name): string
    {
        return 'set' . ucfirst($name);
    }

    /**
     * Get the property name contained in a getter function name
     */
    private static function propertyNameFromGetter(string $getter): string
    {
        return lcfirst(
            substr($getter, 3)
        );
    }
}
