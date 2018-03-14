#!/usr/bin/env php
<?php

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