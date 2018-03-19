#!/usr/bin/env php
<?php

require "dbip-client.class.php";

// Uncomment and fill with your key if you have one
// DBIP\APIKey::set("YOUR_API_KEY_GOES_HERE");

// get IP address from command line arguments
$ipAddress = $argv[1] or die("usage: {$argv[0]} <ip_address>\n");

// lookup IP address information
$addrInfo = DBIP\Address::lookup($ipAddress);

var_dump($addrInfo);
