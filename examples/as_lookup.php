#!/usr/bin/env php
<?php

require "dbip-client.class.php";

DBIP\APIKey::set("ead221f573879ce90dd9d26caec11dc79752a86b");

$myAsn = DBIP\Address::lookup()->asNumber;
$asInfo = DBIP\ASN::lookup(16591);

var_dump($asInfo);
