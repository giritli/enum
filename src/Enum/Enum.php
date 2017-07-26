<?php

namespace Giritli\Enum;

use Giritli\Enum\Exception\NoDefaultValueException;
use Giritli\Enum\Exception\ValueNotFoundException;
use ReflectionClass;
use JsonSerializable;

abstract class Enum implements JsonSerializable
{


    /**
     * The value of the enum. Must be compatible with the enum constant values.
     * 
     * @var mixed
     */ 
    protected $value = null;


    /**
     * Name of the enum constant.
     * 
     * @var string
     */ 
    protected $key = null;


    /**
     * The ordinal of the enum value.
     * 
     * @var integer
     */
    protected $ordinal = -1;


    /**
     * The default value of the enum.
     * 
     * @var mixed
     */
    protected $default = null;
    
    
    /**
     * Can the enum be null?
     */
    protected $nullable = false;


    /**
     * Static cache of all loaded enum values.
     * 
     * @var array
     */
    protected static $cache = [];


    /**
     * Static cache of all the ordinals of enum values.
     * 
     * @var array
     */
    protected static $ordinals = [];


    /**
     * Instantiate a new enum value.
     *
     * @param mixed $value
     * @throws NoDefaultValueException
     */
    public function __construct($value = null)
    {
        if (is_null($value))
        {
            if ($this->nullable)
            {
                static::generateCache();
                
                return;
            }
            
            if ($this->default)
            {
                return $this->__construct($this->default);
            }
            else
            {
                throw new NoDefaultValueException('No enum default exists.');
            }
        }

        static::generateCache();

        if ($value instanceof Enum)
        {
            $this->loadValue($value->getValue());
        }
        else
        {
            $this->loadValue($value);
        }
    }


    /**
     * Generate cache for current class and also cache the
     * ordinal value.
     */
    protected static function generateCache()
    {
        $class = static::class;

        if (!isset(static::$cache[$class]))
        {
            $reflection = new ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
            $i = 0;
            foreach (static::$cache[$class] as $constant => $value)
            {
                static::$ordinals[$class][$constant] = $i++;
            }
        }

    }


    /**
     * Load the value of the enum.
     * 
     * @param mixed $value
     * @throws ValueNotFoundException
     */
    protected function loadValue($value)
    {
        $class = static::class;
        $key = array_search($value, static::$cache[$class], true);

        if ($key === false)
        {
            throw new ValueNotFoundException('Enum value not found.');
        }

        $this->key = $key;
        $this->value = $value;
        $this->ordinal = static::$ordinals[$class][$key];
    }


    /**
     * Allow instantiation of enum by calling Enum::key() and
     * returning a new instance of an enum.
     * 
     * @param string $name
     * @param array $arguments
     * @return Enum
     */
    public static function __callStatic($name, $arguments)
    {
        static::generateCache();
        $class = static::class;

        if (isset(static::$cache[$class][$name]))
        {
            return new static(static::$cache[$class][$name]);
        }

        return new static($name);
    }


    /**
     * Get all enum values.
     * 
     * @return mixed[]
     */
    public static function getValues()
    {
        static::generateCache();

        return static::$cache[static::class];
    }


    /**
     * Get all enum value ordinals.
     * 
     * @return int[]
     */
    public static function getOrdinals()
    {
        static::generateCache();

        return static::$ordinals[static::class];
    }


    /**
     * Get all enum keys.
     * 
     * @return array
     */
    public static function getKeys()
    {
        static::generateCache();

        return array_keys(static::$ordinals[static::class]);
    }


    /**
     * Get the enum value.
     * 
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * Get the enum value's key.
     * 
     * @return string;
     */
    public function getKey()
    {
        return $this->key;
    }


    /**
     * Get the current ordinal value of the enum.
     * 
     * @return integer
     */
    public function getOrdinal()
    {
        return $this->ordinal;
    }
    
    
    /**
     * Compare current value to given value.
     * 
     * @returns boolean;
     */
    public function is($value)
    {
        return (string) $this->getValue() === (string) $value;
    }


    /**
     * Return string representation of enum value.
     * 
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getValue();
    }
    
    
    /**
     * Return string value for json representation.
     * 
     * @return string
     */
    public function jsonSerialize()
    {
        return $this->__toString();
    }
}
