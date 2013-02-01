# DomainObject

This is a simple class that signifies classes that extend it are a
[DomainObject](http://c2.com/cgi/wiki?DomainObject). In a more practical sense
it offers a [properties](http://en.wikipedia.org/wiki/Property_(programming) )
implementation, that is lacking from PHP.

## Why

We believe in a plain old php objects (POPO) for modelling your domain. These
objects should hold no logic other than their core values. A DomainObject
should have a direct link to an entity in your Universe of Discourse.

This class helps you do that in a generic way. It has some niceties such as
support for accessing your properties through both functions and object
property notation. But mostly, it is a strong signal that the class that's
extending it is, in fact, a DomainObject.

### Why not simply rely on public variables?

Using simple variables like:

```php
class Person
{
    public $name;
}
```

Works pretty well for most simple properties.

Imagine the following though:

```php
class Person
{
    public $firstName;

    public $lastName;

    public function getFullName()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
```

In order to get the full name for a person (first + last), you need to write a
method. Now you have to mix both properties and methods in your API. This is
not very consistent and rather inflexible.

## Example

```php
<?php

use Angrybytes\DomainObject;

use \InvalidArgumentException;

class BlogPost extends DomainObject
{
    private $title;

    private $contents;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        // You can do simple sanity checks in your setters
        if (strlen($title) < 3) {
            throw new InvalidArgumentException('Title should be 3 or more characters long');
        }

        $this->title = $title;

        return $this;
    }

    public function getContents()
    {
        return $this->contents;
    }

    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }
}
```

Using this you can:

```php
<?php

$post = new BlogPost;

// Set properties
$post
    ->setTitle('This is the title for my blog post')
    ->setContents('foo');

// Retrieve properties using property notation
echo $post->title;
echo $post->contents;

// Retrieve data in array form for easy serialization
$json = json_encode(
    $post->toArray()
);
```

