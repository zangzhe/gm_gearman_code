#!/usr/bin/php -q
<?php

require_once ('gm_shared.php');
require_once ('Net/Gearman/Worker.php');

try
{
    gm::log_msg ("[gm_worker] starting worker...");
    $worker = new Net_Gearman_Worker (gm::$servers);
    $worker->addAbility ('Example3');
    $worker->beginWork ();
}
catch (Net_Gearman_Exception $e)
{
    gm::log_msg ("[gm_worker] exception caught: " .  $e->getMessage());
}
