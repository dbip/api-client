#!/usr/bin/env php
<?php

require "dbip-client.class.php";

DBIP\APIKey::set("YOUR_API_KEY_GOES_HERE");

// fetch API key information from server
$keyInfo = DBIP\APIKey::info();

var_dump($keyInfo);