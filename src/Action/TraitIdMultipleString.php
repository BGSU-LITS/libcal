<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Client;

trait TraitIdMultipleString
{
    /**
     * The IDs to retrieve.
     *
     * @var string[] $id
     */
    public array $id;

    /**
     * Instantiate object with dependencies and options.
     *
     * @param Client $client Client to send requests to the LibCal API.
     * @param string|string[] $id The ID or IDs to retrieve.
     */
    public function __construct(Client $client, $id)
    {
        parent::__construct($client);

        if (!\is_array($id)) {
            $id = [$id];
        }

        $this->id = $id;
    }

    /**
     * Add ID param to a URI.
     *
     * @param string $uri The URI to add ID param to.
     * @return string The URI with added ID param.
     */
    protected function addId(string $uri): string
    {
        return self::addParam($uri, \implode(',', $this->id));
    }
}
