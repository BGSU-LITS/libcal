<?php

declare(strict_types=1);

namespace Lits\LibCal\Data;

trait TraitQuestions
{
    /** @var array<string,string|string[]> $questions */
    public array $questions = [];

    /**
     * @param string|string[] $value
     * @throws \JsonMapper_Exception
     */
    public static function undefinedPropertyHandler(
        object $object,
        string $property,
        $value
    ): void {
        if (!self::isQuestion($property)) {
            throw new \JsonMapper_Exception(
                'JSON property "' . $property . '" does not exist' .
                ' in object of type ' . \get_class($object)
            );
        }

        if (isset($object->questions) && \is_array($object->questions)) {
            $object->questions[$property] = $value;
        }
    }

    protected static function mapper(): \JsonMapper
    {
        $mapper = parent::mapper();
        $mapper->bExceptionOnUndefinedProperty = false;
        $mapper->undefinedPropertyHandler = [
            static::class,
            'undefinedPropertyHandler',
        ];

        return $mapper;
    }

    private static function isQuestion(string $property): bool
    {
        $result = \filter_var(
            $property,
            \FILTER_VALIDATE_REGEXP,
            ['options' => ['regexp' => '/^q\d+$/']]
        );

        return $result !== false;
    }

    /** @return mixed */
    public function __get(string $property)
    {
        if ($this->__isset($property)) {
            return $this->questions[$property];
        }

        return null;
    }

    public function __isset(string $property): bool
    {
        return self::isQuestion($property) &&
            isset($this->questions[$property]);
    }

    /** @param string|string[] $value */
    public function __set(string $property, $value): void
    {
        if (self::isQuestion($property)) {
            $this->questions[$property] = $value;
        }
    }

    public function __unset(string $property): void
    {
        if (self::isQuestion($property)) {
            unset($this->questions[$property]);
        }
    }
}
