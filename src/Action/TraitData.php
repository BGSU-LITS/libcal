<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Client;
use Lits\LibCal\Data;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;

trait TraitData
{
    /** The data to post. */
    public Data $data;

    /**
     * Post to URI with the predefined data.
     *
     * @param string $uri The URI to post to.
     * @return string Response from the API.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    final public function postWithData(string $uri): string
    {
        return $this->client->post(
            $uri,
            $this->data
        );
    }

    /**
     * Instantiate object with dependencies and options.
     *
     * @param Client $client Client to send requests to the LibCal API.
     * @param Data $data The data to post.
     */
    final public function __construct(Client $client, Data $data)
    {
        parent::__construct($client);

        $this->data = $data;
    }
}
