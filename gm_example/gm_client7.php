#!/usr/bin/php -q
<?php

require_once ('gm_shared.php');
require_once ('Net/Gearman/Client.php');

$client = new Net_Gearman_Client (gm::$servers);

$set = new Net_Gearman_Set();
$task = new Net_Gearman_Task ('Example2', array ('date'));
$task->attachCallback ("complete",Net_Gearman_Task::TASK_COMPLETE);
$task->attachCallback ("fail",Net_Gearman_Task::TASK_FAIL);
$set->addTask ($task);
$client->runSet ($set);

function complete ($func, $handle, $result) {
    gm::log_msg ("[gm_client] complete ($handle/$func)");
    gm::log_msg ("[gm_client] result:\n" . print_r ($result, true));
}

function fail ($task) {
    gm::log_msg ("[gm_client] fail, task: " . print_r ($task, true));
}
