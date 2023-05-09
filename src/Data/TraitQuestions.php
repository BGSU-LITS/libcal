<?php

declare(strict_types=1);

namespace Lits\LibCal\Data;

use Lits\LibCal\Data;

/** Supports questions added as properties to a JSON object as a collection. */
trait TraitQuestions
{
    /** @var array<string,string|string[]> $questions Questions collection. */
    public array $questions = [];

    /**
     * Add parameters that appear to be questions to the question collection.
     *
     * @param object $object The object using this trait.
     * @param string $property A property name of the JSON object.
     * @param string|string[] $value The value of the property.
     * @throws \JsonMapper_Exception
     */
    final public static function undefinedPropertyHandler(
        object $object,
        string $property,
        $value
    ): void {
        if (!self::isQuestion($property)) {
            if (!Data::$strictMapping) {
                /** @phpstan-ignore-next-line */
                $object->$property = $value;

                return;
            }

            throw new \JsonMapper_Exception(
                'JSON property "' . $property . '" does not exist' .
                ' in object of type ' . \get_class($object)
            );
        }

        if (isset($object->questions) && \is_array($object->questions)) {
            $object->questions[$property] = $value;
        }
    }

    /**
     * Create a JsonMapper object with necessary configuration.
     *
     * @return \JsonMapper Configured to handle undefined properties.
     */
    final protected static function mapper(): \JsonMapper
    {
        $mapper = parent::mapper();
        $mapper->bExceptionOnUndefinedProperty = false;
        $mapper->undefinedPropertyHandler = [
            static::class,
            'undefinedPropertyHandler',
        ];

        return $mapper;
    }

    /**
     * Check if the given property name appears to be a question.
     *
     * @param string $property A property name of the JSON object.
     * @return bool If the name is the letter q followed by an integer.
     */
    private static function isQuestion(string $property): bool
    {
        $result = \filter_var(
            $property,
            \FILTER_VALIDATE_REGEXP,
            ['options' => ['regexp' => '/^q\d+$/']]
        );

        return $result !== false;
    }

    /**
     * Magic method to handle getting a question instead of a property.
     *
     * @param string $property A property name of a question to get.
     * @return string|string[]|null The question if available, otherwise null.
     */
    final public function __get(string $property)
    {
        if ($this->__isset($property)) {
            return $this->questions[$property];
        }

        return null;
    }

    /**
     * Magic method to check if a question has been set.
     *
     * @param string $property A property name of a question to check for.
     * @return bool Whether the property is a question and has been set.
     */
    final public function __isset(string $property): bool
    {
        return self::isQuestion($property) &&
            isset($this->questions[$property]);
    }

    /**
     * Magic method to set a question.
     *
     * @param string $property A property name of a question to set.
     * @param string|string[] $value A value to be set.
     */
    final public function __set(string $property, $value): void
    {
        if (self::isQuestion($property)) {
            $this->questions[$property] = $value;
        }
    }

    /**
     * Magic method to unset a question.
     *
     * @param string $property A property name of a question to unset.
     */
    final public function __unset(string $property): void
    {
        if (self::isQuestion($property)) {
            unset($this->questions[$property]);
        }
    }
}
