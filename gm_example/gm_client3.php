#!/usr/bin/php -q
<?php

require_once ('gm_shared.php');
require_once ('Net/Gearman/Client.php');

$client = new Net_Gearman_Client (gm::$servers);

$set = new Net_Gearman_Set ();
$task = new Net_Gearman_Task ('Example1', array ('date'));
$task->attachCallback ("complete",Net_Gearman_Task::TASK_COMPLETE);
$set->addTask ($task);
$client->runSet ($set);

function complete ($func, $handle, $result) {
    gm::log_msg ("[gm_client] complete ($handle/$func)");
    gm::log_msg ("[gm_client] result: " . $result['result']);
}
