# libcal
PHP client library for the [Springshare LibCal](https://springshare.com/libcal/) API.

## Getting Started
You may want to begin by reviewing the [Overview of the LibCal API](https://ask.springshare.com/libcal/faq/1407) from the Springshare Help Center. This client uses the API v1.1 endpoints.

This package is developed for [PHP 7.4 and above](https://www.php.net/supported-versions.php). You should use [Composer](https://getcomposer.org/) to install this package and its dependencies. For example:
```
php composer.phar require bgsu-lits/libcal guzzlehttp/guzzle guzzlehttp/psr7 desarrolla2/cache
```

### Required Dependencies
#### [PSR-18 HTTP Client](https://www.php-fig.org/psr/psr-18/)
The client requires an [implementation of `psr/http-client`](https://packagist.org/providers/psr/http-client-implementation). This documentation will use [`guzzlephp/guzzle`](https://packagist.org/packages/guzzlehttp/guzzle) for examples, but any implementation may be used instead.

#### [PSR-17 HTTP Factories](https://www.php-fig.org/psr/psr-17/)
The client requires an [implementation of `psr/http-factory`](https://packagist.org/providers/psr/http-factory-implementation). This documentation will use [`guzzlephp/psr7`](https://packagist.org/packages/guzzlehttp/psr7) for examples, but any implementation may be used instead.

#### [PSR-7 HTTP Message Interface](https://www.php-fig.org/psr/psr-7/)
The client requires an [implementation of `psr/http-message`](https://packagist.org/providers/psr/http-message-implementation). This documentation will use [`guzzlephp/psr7`](https://packagist.org/packages/guzzlehttp/psr7) for examples, but any implementation may be used instead.

### Optional Dependencies
#### [PSR-16 Simple Cache](https://www.php-fig.org/psr/psr-16/)
The client can use an [implementation of `psr/simple-cache`](https://packagist.org/providers/psr/simple-cache-implementation). This documentation will use [`desarrolla2/cache`](https://packagist.org/packages/desarrolla2/cache) for examples, but any implementation may be used instead.

## Determining Client Credentials
### Host
The host for the client will be the hostname of your LibCal instance, i.e. `<institution>.libcal.com`. For examples, we'll use BGSU's host of `bgsu.libcal.com`.

### Client ID and Secret
Visit the Dashboard of your LibCal instance, and navigate to Admin > API. Choose the API Authentication tab, and use the Create New Application button under the Applications section. You may specify any Application Name and Description you prefer and leave checked any of the scopes you want to access with the client.

Once you've created the application, you will need to copy the values from the Client Id and Client Secret columns of the Applications table. For examples, we'll use a Client ID of `100` and a Client Secret of `61483dcf150d97c921abbe1f8024eb2e`.

## Instantiating the API Client
Now that you've gathered the dependencies and credentials, you can instantiate the API client. Below is an example PHP file that will include Composer's autoloader and create the API client with credentials and dependencies. This is using the examples we've specified above, with comments showing the interfaces the dependencies must implement.

```php
<?php
require __DIR__ . '/vendor/autoload.php';

$client = new \Lits\LibCal\Client(
    'bgsu.libcal.com',
    '100',
    '61483dcf150d97c921abbe1f8024eb2e',
    new GuzzleHttp\Client(),           // Psr\Http\Client\ClientInterface
    new GuzzleHttp\Psr7\HttpFactory(), // Psr\Http\Message\RequestFactoryInterface
    new GuzzleHttp\Psr7\HttpFactory(), // Psr\Http\Message\StreamFactoryInterface
    new Desarrolla2\Cache\Memory()     // Psr\SimpleCache\CacheInterface
);
```

## Using the API Client
With an instantiated API Client, you can access API endpoints in a fluid fashion. For example, to access the `/space/locations` endpoint, you can do the following:
```php
$locations = $client->space()->locations()->send();
```

Parameters can also be added, typically through methods beginning with `set`. For example, the `/space/locations` endpoint has both `details` and `admin_only` as parameters:
```php
$locations = $client->space()->locations()
    ->setDetails()
    ->setAdminOnly()
    ->send();
```

Some endpoints require parameters to be specified, typically IDs. For example,
to access `/space/item/:id` with an ID of 100:
```php
$item = $client->space()->item(100)->send();
```

The resulting data from API calls will be mapped to a PHP object or array of objects of class `Lits\LibCal\Data`. In turn, the properties should be accessible from those objects in the correct format.

If the endpoint requires data to POST, that data is specified in a specific payload object. These objects can be created from POST data submitted by the user. For example, to use the `/space/reserve` endpoint to reserve a space:
```php
$_POST = [
    'start' => '2021-12-31T10:00:00-0400',
    'fname' => 'Freddie',
    'lname' => 'Falcon',
    'email' => 'ffalcon@bgsu.edu',
    'bookings' => [
        [
            'id' => 100,
            'to' => '2021-12-31T11:00:00-0400',
        ],
    ],
];

$data = new \Lits\LibCal\Data\Space\Reserve\PayloadReserveSpaceData
    ::fromArray($_POST);

$response = $client->space()->reserve($data)->send();
```

### Caching Results
Providing a Simple Cache client to the API client is optional. If it is provided, it will always be used to cache authenticated sessions. It then can also be used to cache the results of most GET endpoints. This can be accomplished by calling the `cache()` method on those endpoints before `send()`:
```php
$locations = $client->space()->locations()->cache()->send();
```

It is recommended to consult your Simple Cache client's documentation on how to best set up the cache to preserve entries between processes.

## Limitations
This client currently only implements the Spaces/Seats endpoints under `/space`.

## Related Projects
[An application that provides a public interface for space booking](https://github.com/bgsu-lits/book) using this library is also available from the BGSU University Libraries.

The IUPUI University Library provides an example [LibCal Room Reservation Application](https://github.com/iupui-university-library/libcal_rooms) that includes their own implementation of a client.

## Development
This client was developed by the [Library Information Technology Services](https://github.com/BGSU-LITS) of the [University Libraries at Bowling Green Sate University](https://www.bgsu.edu/library/). The code is licensed under the [MIT License](LICENSE). Issues may be reported to the [GitHub Project](https://github.com/BGSU-LITS/libcal).

Contributions are also welcome, preferably via pull requests. Before submitting changes, please run `test` command via Composer (`php composer.phar test`) to check that code conforms to the project standards.
