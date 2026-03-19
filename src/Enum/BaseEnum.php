<?php

namespace Twix\Enum;

/**
 * Base Enum class
 */
abstract class BaseEnum
{
    private $value;

    final private function __construct($value)
    {
        $this->value = $value;
    }

    final public function getValue()
    {
        return $this->value;
    }

    final public function __toString(): string
    {
        return (string) $this->value;
    }

    final public static function __callStatic($name, $arguments)
    {
        $constant = constant('static::' . $name);
        return new static($constant);
    }

    final public static function fromValue($value): ?self
    {
        if (in_array($value, static::getValidValues(), true)) {
            return new static($value);
        }
        return null;
    }

    abstract public static function getValidValues(): array;
}
