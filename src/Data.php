<?php

declare(strict_types=1);

namespace Lits\LibCal;

use Lits\LibCal\Exception\DataException;

/** Data transfer from and to JSON. */
abstract class Data
{
    /** Maximum depth for the PHP json_encode() function. */
    public const JSON_DEPTH = 512;

    /** Whether to enforce strict mapping of JSON to objects. */
    public static bool $strictMapping = false;

    /**
     * Instantiate object without setting any property values.
     *
     * Note: Parameters without default values will be left uninitialized.
     */
    final public function __construct()
    {
    }

    /**
     * Instantiate object with values for properties specified within an array.
     *
     * @param mixed[] $data Data to load into properties.
     * @return static Object with loaded data.
     * @throws DataException
     */
    final public static function fromArray(array $data): self
    {
        try {
            $mapper = static::mapper();
            $mapper->bEnforceMapType = false;

            /** @var static */
            return $mapper->map($data, new static());
        } catch (\Throwable $exception) {
            throw new DataException(
                'Data could not be loaded to object properties',
                0,
                $exception
            );
        }
    }

    /**
     * Instantiate object with values for properties from a JSON object.
     *
     * @param string $data JSON object to load into properties.
     * @return static Object with loaded data.
     * @throws DataException
     */
    final public static function fromJson(string $data): self
    {
        $object = static::decodeJson($data);

        if (!\is_object($object)) {
            throw new DataException('Specified JSON is not an object');
        }

        try {
            /** @var static */
            return static::mapper()->map($object, new static());
        } catch (\Throwable $exception) {
            throw new DataException(
                'Data could not be loaded to object properties',
                0,
                $exception
            );
        }
    }

    /**
     * Get properties as a JSON object.
     *
     * @return string JSON object.
     * @throws DataException
     */
    public function json(): string
    {
        try {
            return \json_encode($this, \JSON_THROW_ON_ERROR);
        } catch (\Throwable $exception) {
            throw new DataException(
                'JSON could not be encoded',
                0,
                $exception
            );
        }
    }

    /**
     * Create array of objects with values from an array of JSON objects.
     *
     * @param string $data JSON array of objects to load.
     * @return static[] Objects with loaded data.
     * @throws DataException
     */
    final public static function fromJsonAsArray(string $data): array
    {
        $array = static::decodeJson($data);

        if (!\is_array($array)) {
            throw new DataException('Specified JSON is not an array');
        }

        try {
            /** @var static[] */
            return static::mapper()->mapArray($array, [], static::class);
        } catch (\Throwable $exception) {
            throw new DataException(
                'Data could not be loaded to object properties',
                0,
                $exception
            );
        }
    }

    /**
     * Decode JSON.
     *
     * @param string $data JSON to decode.
     * @return mixed The decoded JSON.
     * @throws DataException
     */
    protected static function decodeJson(string $data)
    {
        try {
            return \json_decode(
                $data,
                false,
                self::JSON_DEPTH,
                \JSON_THROW_ON_ERROR
            );
        } catch (\Throwable $exception) {
            throw new DataException(
                'JSON could not be decoded',
                0,
                $exception
            );
        }
    }

    /**
     * Create a JsonMapper object with necessary configuration.
     *
     * @return \JsonMapper A configured JsonMapper object.
     */
    protected static function mapper(): \JsonMapper
    {
        $mapper = new \JsonMapper();

        $mapper->bExceptionOnMissingData = self::$strictMapping;
        $mapper->bExceptionOnUndefinedProperty = self::$strictMapping;

        return $mapper;
    }
}
