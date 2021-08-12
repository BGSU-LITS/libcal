<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Client;

trait TraitIdMultipleString
{
    /** @var string[] $id The ids to retrieve. */
    public array $id;

    /**
     * Instantiate object with dependencies and options.
     *
     * @param Client $client Client to send requests to the LibCal API.
     * @param string|string[] $id The id or list of ids to retrieve.
     */
    final public function __construct(Client $client, $id)
    {
        parent::__construct($client);

        if (!\is_array($id)) {
            $id = [$id];
        }

        $this->id = $id;
    }

    /**
     * Add id param to a URI.
     *
     * @param string $uri The URI to add param to.
     * @return string The URI with added param.
     */
    final protected function addId(string $uri): string
    {
        return self::addParam($uri, \implode(',', $this->id));
    }
}
