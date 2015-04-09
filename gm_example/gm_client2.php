#!/usr/bin/php -q
<?php

require_once ('gm_shared.php');
require_once ('Net/Gearman/Client.php');

$client = new Net_Gearman_Client (gm::$servers);

$set = new Net_Gearman_Set();
$task = new Net_Gearman_Task ('Example1', array ('date'));
$set->addTask ($task);
$client->runSet ($set);
