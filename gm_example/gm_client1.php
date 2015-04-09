#!/usr/bin/php -q
<?php

require_once ('gm_shared.php');
require_once ('Net/Gearman/Client.php');

$client = new Net_Gearman_Client (gm::$servers);
$client->Example1 (array ('date'));

