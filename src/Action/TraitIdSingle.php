<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Client;

trait TraitIdSingle
{
    /** The ID to retrieve. */
    public int $id;

    /**
     * Instantiate object with dependencies and options.
     *
     * @param Client $client Client to send requests to the LibCal API.
     * @param int $id The ID to retrieve.
     */
    public function __construct(Client $client, int $id)
    {
        parent::__construct($client);

        $this->id = $id;
    }

    /**
     * Add ID param to a URI.
     *
     * @param string $uri The URI to add ID param to.
     * @return string The URI with added ID param.
     */
    final protected function addId(string $uri): string
    {
        return self::addParam($uri, $this->id);
    }
}
