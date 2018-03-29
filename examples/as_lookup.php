#!/usr/bin/env php
<?php

require "dbip-client.class.php";

DBIP\APIKey::set("YOUR_API_KEY_GOES_HERE");

$myAsn = DBIP\Address::lookup()->asNumber;
$asInfo = DBIP\ASN::lookup(16591);

var_dump($asInfo);
