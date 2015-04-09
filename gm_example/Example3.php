<?php

class Net_Gearman_Job_Example3 extends Net_Gearman_Job_Common
{
    public function run ($arg)
    {
        if (count ($arg) != 1)
        {
            throw new Net_Gearman_Job_Exception ("must provide exactly one command to run");
        }

        $cmd = $arg[0];

        if ($cmd != "date")
        {
            throw new Net_Gearman_Job_Exception ("command '$cmd' not allowed");
        }

        $cmd .= " 2>&1";

        gm::log_msg ("  [Example3] command: $cmd");

        $output = array ();
        $retval = 0;
        exec ($cmd, $output, $retval);

        $output = join ("\n", $output);
        gm::log_msg ("  [Example3] output: " . $output);

        $result = ($retval == 0) ? true : false;

        return array ('result' => $result, 'output' => $output);
    }
}
