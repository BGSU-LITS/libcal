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

/** Client to send requests to the LibCal API. */
final class Client
{
    /** Version of the LibCal API. */
    public const VERSION = '1.1';

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

    /** Access Token data retrieved from the LibCal API. */
    private ?TokenData $token = null;

    /**
     * Instantiate object with necessary settings and dependencies.
     *
     * @param string $host
     *   Host of LibCal API.
     * @param string $clientId
     *   Client ID generated from LibCal API Authentication.
     * @param string $clientSecret
     *   Client Secret generated from LibCal API Authentication.
     * @param HttpClient $client
     *   PSR-18 HTTP Client implementation.
     * @param HttpRequestFactory $requestFactory
     *   PSR-17 HTTP Request Factory implementation.
     * @param HttpStreamFactory $streamFactory
     *   PSR-17 HTTP Stream Factory implementation.
     */
    public function __construct(
        string $host,
        string $clientId,
        string $clientSecret,
        HttpClient $client,
        HttpRequestFactory $requestFactory,
        HttpStreamFactory $streamFactory
    ) {
        $this->host = $host;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
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
            $message = 'HTTP ' . $response->getStatusCode() . ' response';

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
        if (\is_null($this->token)) {
            $request = $this->request(
                'POST',
                self::VERSION . '/oauth/token',
                false
            );

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

            $this->token = TokenData::fromJson($this->send($request));
        }

        return $this->token;
    }

    private static function parseJsonError(object $json): string
    {
        if (isset($json->errors)) {
            if (\is_array($json->errors)) {
                return ': ' . \implode('; ', $json->errors);
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
