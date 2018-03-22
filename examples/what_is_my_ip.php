#!/usr/bin/env php
<?php

require "dbip-client.class.php";

echo "My IP address is " . DBIP\Address::lookup()->ipAddress;
