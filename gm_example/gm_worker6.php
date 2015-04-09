#!/usr/bin/php -q
<?php

require_once ('gm_shared.php');
require_once ('Net/Gearman/Worker.php');

try
{
    gm::log_msg ("[gm_worker] starting worker...");
    $worker = new Net_Gearman_Worker (gm::$servers);
    $worker->addAbility ('Example2');
    $worker->beginWork ('monitor');
}
catch (Net_Gearman_Exception $e)
{
    gm::log_msg ("[gm_worker] exception caught: " .  $e->getMessage());
}

function monitor ($idle, $time_of_last_job)
{
    $idle_str = ($idle) ? 'idle' : 'not idle';
    $time_of_last_job_str = date ('r', $time_of_last_job);
    gm::log_msg ("[gm_worker] status: $idle_str, time of last job: $time_of_last_job_str");
}   

