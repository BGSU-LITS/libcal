<?php

declare(strict_types=1);

namespace Lits\LibCal\Data\Space;

use Lits\LibCal\Data;

final class CategorySpaceData extends Data
{
    public int $cid;
    public ?string $name = null;
    public int $formid;
    public bool $public;
    public ?bool $admin_only = null;

    /** @var ItemSpaceData[] $items */
    public ?array $items = null;

    /** @return mixed */
    protected static function decodeJson(string $data)
    {
        /** @var object|mixed[]|null $decoded */
        $decoded = parent::decodeJson($data);

        if (\is_array($decoded)) {
            foreach (\array_keys($decoded) as $key) {
                if (\is_object($decoded[$key])) {
                    $decoded[$key] = self::fixObject($decoded[$key]);
                }
            }

            return $decoded;
        }

        if (\is_object($decoded)) {
            $decoded = self::fixObject($decoded);
        }

        return $decoded;
    }

    private static function fixObject(object $object): object
    {
        if (isset($object->items) && \is_array($object->items)) {
            $object->items = self::fixObjectItems($object->items);
        }

        return $object;
    }

    /**
     * @param mixed[] $items
     * @return mixed[]
     */
    private static function fixObjectItems(array $items): array
    {
        foreach (\array_keys($items) as $key) {
            if (\is_int($items[$key])) {
                $items[$key] = (object) ['id' => $items[$key]];
            }
        }

        return $items;
    }
}
