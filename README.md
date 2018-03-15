# api-client

This is the officially supported PHP 7.x client library for the [db-ip.com](https://db-ip.com/) API services.

More details about API responses are available [here](https://db-ip.com/api/doc.php#addrinfo)

# Usage

See a few examples of command line scripts below that demonstrate usage of the library :

## IP address lookup

```php
require "dbip-client.class.php";

DBIP\APIKey::set("YOUR_API_KEY_GOES_HERE");

// get IP address from command line arguments
$ipAddress = $argv[1] or die("usage: {$argv[0]} <ip_address>\n");

// lookup IP address information
$addrInfo = DBIP\Address::lookup($ipAddress);

var_dump($addrInfo);
```

## Get API key information

```php
require "dbip-client.class.php";

DBIP\APIKey::set("YOUR_API_KEY_GOES_HERE");

// fetch API key information from server
$keyInfo = DBIP\APIKey::info();

var_dump($keyInfo);
```

## Error handling

```php
require "dbip-client.class.php";

DBIP\APIKey::set("YOUR_API_KEY_GOES_HERE");

foreach ([ "1.2.3.4", "1.2.3.999", "5.6.7.8" ] as $ipAddress) {
	try {
		echo DBIP\Address::lookup($ipAddress)->countryCode . "\n";
	} catch (DBIP\ServerError $e) {
		if ($e->getErrorCode() === DBIP\ErrorCode::INVALID_ADDRESS) {
			echo "{$ipAddress} is not a valid IP address\n";
		}
	}
}
```
