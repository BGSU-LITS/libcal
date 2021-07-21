<?php

declare(strict_types=1);

namespace Lits\LibCal\Action;

use Lits\LibCal\Data;
use Lits\LibCal\Exception\ClientException;

trait TraitCache
{
    /** Whether caching should be enabled. */
    protected bool $cache = false;

    /** @var int|\DateInterval $cache_ttl Seconds or interval to cache data. */
    protected $cache_ttl = 60;

    /**
     * Enable caching for this action.
     *
     * @param int|\DateInterval|null $ttl Seconds or interval to cache data.
     * @return self A reference to this object for method chaining.
     */
    final public function cache($ttl = null): self
    {
        $this->cache = true;

        if (!\is_null($ttl)) {
            $this->cache_ttl = $ttl;
        }

        return $this;
    }

    /**
     * Memoize results if caching was enabled.
     *
     * @param string $uri The URI that is being memoized.
     * @param callable $callback The function that will be memoized.
     * @return Data|Data[] The value of the memoized function.
     * @throws ClientException
     */
    final protected function memoize(string $uri, callable $callback)
    {
        return $this->client->memoize(
            $uri,
            $this->cache,
            $this->cache_ttl,
            $callback
        );
    }
}
