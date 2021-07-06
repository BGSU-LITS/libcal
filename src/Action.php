<?php

declare(strict_types=1);

namespace Lits\LibCal;

/** Actions to perform on the LibCal API. */
abstract class Action
{
    /** Client to send requests to the LibCal API. */
    protected Client $client;

    /**
     * Instantiate object with dependencies.
     *
     * @param Client $client Client to send requests to the LibCal API.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /** @param mixed $value */

    /**
     * Add query string parameter to a URI.
     *
     * @param string $uri URI to add query string parameter to.
     * @param string $name Name of the query string parameter.
     * @param mixed $value Value of the query string parameter.
     * @return string The URI with the query string parameter added, preceded
     *  by either a question mark or ampersand as necessary.
     */
    protected static function addQuery(
        string $uri,
        string $name,
        $value
    ): string {
        if (\is_null($value)) {
            return $uri;
        }

        if (\is_bool($value)) {
            $value = $value ? '1' : '0';
        }

        $uri .= \strpos($uri, '?') === false ? '?' : '&';
        $uri .= $name . '=' . (string) $value;

        return $uri;
    }

    /**
     * Add path parameter to a URI.
     *
     * @param string $uri URI to add path parameter to.
     * @param mixed $value Value of the path parameter.
     * @return string The URI with the path parameter added, preceded by a
     *  slash as necessary.
     */
    protected static function addParam(string $uri, $value): string
    {
        return \rtrim($uri, '/') . '/' . (string) $value;
    }
}
