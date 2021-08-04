<?php

declare(strict_types=1);

namespace Lits\LibCal;

use Lits\LibCal\Action\SpaceAction;
use Lits\LibCal\Data\TokenData;
use Lits\LibCal\Exception\ClientException;
use Lits\LibCal\Exception\DataException;
use Lits\LibCal\Exception\NotFoundException;
use Psr\Http\Client\ClientExceptionInterface as HttpClientException;
use Psr\Http\Client\ClientInterface as HttpClient;
use Psr\Http\Message\RequestFactoryInterface as HttpRequestFactory;
use Psr\Http\Message\RequestInterface as HttpRequest;
use Psr\Http\Message\StreamFactoryInterface as HttpStreamFactory;
use Psr\SimpleCache\CacheInterface as Cache;

/** Client to send requests to the LibCal API. */
final class Client
{
    /** Version of the LibCal API. */
    public const VERSION = '1.1';

    /** Offset time for OAuth expires_in in seconds. */
    private const EXPIRES_IN_OFFSET = 30;

    /** HTTP code for Not Found. */
    private const HTTP_NOT_FOUND = 404;

    /** HTTP code for OK. */
    private const HTTP_OK = 200;

    /** Host of LibCal API. */
    private string $host;

    /** Client ID generated from LibCal API Authentication. */
    private string $clientId;

    /** Client Secret generated from LibCal API Authentication. */
    private string $clientSecret;

    /** PSR-18 HTTP Client implementation. */
    private HttpClient $client;

    /** PSR-17 HTTP Request Factory implementation. */
    private HttpRequestFactory $requestFactory;

    /** PSR-17 HTTP Stream Factory implementation. */
    private HttpStreamFactory $streamFactory;

    /** PSR-16 Simple Cache implementation. */
    private ?Cache $cache = null;

    /** @var Data[]|Data[][] $memoized Memoized data fallback. */
    private array $memoized = [];

    /**
     * Instantiate object with necessary settings and dependencies.
     *
     * @param string $host Host of LibCal API.
     * @param string $clientId Client ID generated by LibCal API.
     * @param string $clientSecret Client Secret generated by LibCal API.
     * @param HttpClient $client PSR-18 HTTP Client.
     * @param HttpRequestFactory $requestFactory PSR-17 HTTP Request Factory.
     * @param HttpStreamFactory $streamFactory PSR-17 HTTP Stream Factory.
     */
    public function __construct(
        string $host,
        string $clientId,
        string $clientSecret,
        HttpClient $client,
        HttpRequestFactory $requestFactory,
        HttpStreamFactory $streamFactory,
        ?Cache $cache = null
    ) {
        $this->host = $host;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->cache = $cache;
    }

    /**
     * Get response from API.
     *
     * @param string $uri URI path and optional query parameters for the API.
     * @return string Response from the API.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function get(string $uri): string
    {
        return $this->send($this->request('GET', $uri));
    }

    /**
     * Memoize the result of a function call with cache or memory.
     *
     * @param string $uri The URI that is being memoized.
     * @param bool $cache Whether to cache the result of the function.
     * @param int|\DateInterval|null $ttl Seconds or interval TTL for cache.
     * @param callable $callback The function that will be memoized.
     * @return Data|Data[] The value of the memoized function.
     * @throws ClientException
     */
    public function memoize(string $uri, bool $cache, $ttl, callable $callback)
    {
        /** @phpstan-ignore-next-line */
        $key = \preg_replace('/[^A-Za-z0-9_.]+/', '.', $uri);

        if (!\is_string($key)) {
            throw new ClientException(
                'Cache key could not be created from URI'
            );
        }

        $key = \trim($key, '.');
        $api = \filter_var($key, \FILTER_VALIDATE_REGEXP, [
            'options' => ['regexp' => '/^api\./'],
        ]);

        if ($api === false) {
            $key = 'api.' . $key;
        }

        if ($cache) {
            $value = $this->memoizeGet($key);

            if (!\is_null($value)) {
                return $value;
            }
        }

        /** @var Data|Data[] $value */
        $value = $callback($uri);

        if ($cache) {
            $this->memoizeSet($key, $ttl, $value);
        }

        return $value;
    }

    /**
     * Post data to the API.
     *
     * @param string $uri URI path for the API.
     * @param Data|null $data Data to be posted to the API.
     * @return string Response from the API.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    public function post(string $uri, ?Data $data = null): string
    {
        $request = $this->request('POST', $uri);

        if (!\is_null($data)) {
            try {
                $request = $request->withHeader(
                    'Content-Type',
                    'application/json'
                );

                $request = $request->withBody(
                    $this->streamFactory->createStream($data->json())
                );
            } catch (\InvalidArgumentException $exception) {
                throw new ClientException(
                    'HTTP request could not be created',
                    0,
                    $exception
                );
            }
        }

        return $this->send($request);
    }

    /**
     * Retrieve actions for LibCal Space API.
     *
     * @return SpaceAction Actions for LibCal Space API.
     */
    public function space(): SpaceAction
    {
        return new SpaceAction($this);
    }

    /**
     * Get any memoized value from cache or memory.
     *
     * @param string $key The key of the value to retrieve.
     * @return Data|Data[]|null The value to be retrieved.
     * @throws ClientException
     * @psalm-suppress MissingThrowsDocblock
     */
    private function memoizeGet(string $key)
    {
        if (!\is_null($this->cache)) {
            try {
                /** @var Data|Data[]|null $value */
                $value = $this->cache->get($key);
            } catch (\Throwable $exception) {
                throw new ClientException(
                    'Cache could not be accessed',
                    0,
                    $exception
                );
            }

            if (!\is_null($value)) {
                return $value;
            }
        }

        if (isset($this->memoized[$key])) {
            return $this->memoized[$key];
        }

        return null;
    }

    /**
     * Set a value to cache or memory.
     *
     * @param string $key The key of the value to set.
     * @param int|\DateInterval|null $ttl Seconds or interval TTL for cache.
     * @param Data|Data[] $value The value to be set.
     * @psalm-suppress MissingThrowsDocblock
     */
    private function memoizeSet(string $key, $ttl, $value): void
    {
        if (!\is_null($this->cache)) {
            if (\is_null($ttl) && $value instanceof TokenData) {
                $ttl = $value->expires_in - self::EXPIRES_IN_OFFSET;
            }

            try {
                $this->cache->set($key, $value, $ttl);
            } catch (\Throwable $exception) {
                throw new ClientException(
                    'Cache could not be accessed',
                    0,
                    $exception
                );
            }
        }

        $this->memoized[$key] = $value;
    }

    /**
     * Create PSR-7 HTTP Request for the LibCal API.
     *
     * @param string $method HTTP method for the request.
     * @param string $uri URI path and optional query parameters for the API.
     * @param bool $authorize Whether the request needs to be authorized.
     * @return HttpRequest PSR-7 HTTP Request.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    private function request(
        string $method,
        string $uri,
        bool $authorize = true
    ): HttpRequest {
        $request = $this->requestFactory->createRequest(
            $method,
            'https://' . $this->host . '/' . \ltrim($uri, '/')
        );

        if ($authorize) {
            $token = $this->token();

            try {
                $request = $request->withHeader(
                    'Authorization',
                    $token->token_type . ' ' . $token->access_token
                );
            } catch (\InvalidArgumentException $exception) {
                throw new ClientException(
                    'HTTP request could not be created',
                    0,
                    $exception
                );
            }
        }

        return $request;
    }

    /**
     * Send a PSR-7 HTTP Request.
     *
     * @param HttpRequest $request PSR-7 HTTP Request to send.
     * @return string Content of the body of the response from the request.
     * @throws ClientException
     * @throws NotFoundException
     */
    private function send(HttpRequest $request): string
    {
        try {
            $response = $this->client->sendRequest($request);
        } catch (HttpClientException $exception) {
            throw new ClientException(
                'HTTP request could not be sent',
                0,
                $exception
            );
        }

        if ($response->getStatusCode() === self::HTTP_NOT_FOUND) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() !== self::HTTP_OK) {
            $message = 'HTTP ' . (string) $response->getStatusCode() .
                ' response';

            try {
                $message .= self::parseJsonError(
                    (object) \json_decode(
                        $response->getBody()->getContents(),
                        false,
                        Data::JSON_DEPTH,
                        \JSON_THROW_ON_ERROR
                    )
                );
            } catch (\Throwable $exception) {
                throw new ClientException($message, 0, $exception);
            }

            throw new ClientException($message);
        }

        try {
            return $response->getBody()->getContents();
        } catch (\RuntimeException $exception) {
            throw new ClientException(
                'HTTP response could not be read',
                0,
                $exception
            );
        }
    }

    /**
     * Get access token, retrieving from LibCal API if necessary.
     *
     * @return TokenData Access Token data from LibCal API.
     * @throws ClientException
     * @throws DataException
     * @throws NotFoundException
     */
    private function token(): TokenData
    {
        $uri = self::VERSION . '/oauth/token';

        /** @var TokenData $result */
        $result = $this->memoize(
            $uri,
            true,
            null,
            function (string $uri): TokenData {
                $request = $this->request('POST', $uri, false);

                try {
                    $request = $request->withHeader(
                        'Content-Type',
                        'application/x-www-form-urlencoded'
                    );

                    $request = $request->withBody(
                        $this->streamFactory->createStream(\http_build_query([
                            'client_id' => $this->clientId,
                            'client_secret' => $this->clientSecret,
                            'grant_type' => 'client_credentials',
                        ]))
                    );
                } catch (\InvalidArgumentException $exception) {
                    throw new ClientException(
                        'HTTP request could not be created',
                        0,
                        $exception
                    );
                }

                return TokenData::fromJson($this->send($request));
            }
        );

        return $result;
    }

    /**
     * Parse error messages from JSON object provided from LibCal.
     *
     * @param object $json The object of JSON data to parse.
     * @return string Any errors from the object, separated by semicolons and
     *  prefixed with a colon and a space.
     */
    private static function parseJsonError(object $json): string
    {
        if (isset($json->errors)) {
            if (\is_array($json->errors)) {
                /** @var string[] */
                $errors = $json->errors;

                return ': ' . \implode('; ', $errors);
            }

            if (\is_string($json->errors)) {
                return ': ' . $json->errors;
            }
        }

        if (isset($json->error) && \is_string($json->error)) {
            return ': ' . $json->error;
        }

        return '';
    }
}
