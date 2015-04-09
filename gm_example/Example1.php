<?php

class Net_Gearman_Job_Example1 extends Net_Gearman_Job_Common
{
    public function run ($arg)
    {
        $cmd = $arg[0];

        $cmd .= " > /dev/null 2>&1";

        gm::log_msg ("  [Example1] command: $cmd");

        $retval = 0;
        system ($cmd, $retval);

        $result = ($retval == 0) ? true : false;

        return $result;
    }
}
