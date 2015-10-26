[![Build Status](https://travis-ci.org/giritli/enum.svg)](https://travis-ci.org/giritli/enum)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/giritli/enum/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/giritli/enum/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/giritli/enum/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/giritli/enum/?branch=master)

# Enum
PHP lacks enums. This is an implementation of an enum type in PHP. It allows you to define your own enum types and use them as value objects.

## Installation
To install this package using composer, run the following command:

    composer require giritli/enum

## Example usage
Create your initial class that will extend the Enum class.

```php

final class StatusEnum extends Giritli\Enum\Enum {
    
    const draft = 'draft';
    const active = 'active';
    const archived = 'archived';
    const cancelled = 'cancelled';
    
    protected $default = self::draft;
}

```

It is that simple. You now have a fully fledged enum. As the enum class is meant to be a data time, it is recommended that all enum classes be made final but this is of course down to individual use case.

Example usage:

```php

// This status defaults to draft as specified in the class
$status = new StatusEnum();


// You can instantiate an enum by value
$status = new StatusEnum('active');
$status = new StatusEnum(StatusEnum::active);


// Or by name
$status = StatusEnum::draft();


// Get the ordinal value of the enum
$status->getOrdinal(); // 0


// Get the value of the enum
$status->getValue(); // draft
echo $status;


// Get the key of the enum
$status->getKey(); // draft


// Get all values of an enum
$status->getValues();
StatusEnum::getValues();


// Get all ordinal values of an enum
$status->getOrdinals();
StatusEnum::getOrdinals();


// Get all key values of an enum
$status->getKeys();
StatusEnum::getKeys();


```

A key is the name of the class constant and value is the value of the class constant. Usually these values should be identical but there are cases when this is not true.

An enum once instantiated cannot have it's value changed. You will need to instantiate a new object of a different value. Enum values cannot be dynamically altered either.


## How does it work?
Enum values are specified by the class constants that are defined. An enum can be instantiated by passing an enum value, or without passing any value if there is a default enum value. An enum can also be instantiated by calling the enum name as a method.