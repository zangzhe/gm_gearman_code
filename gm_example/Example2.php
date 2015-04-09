<?php

class Net_Gearman_Job_Example2 extends Net_Gearman_Job_Common
{
    public function run ($arg)
    {
        $cmd = $arg[0];

        $cmd .= " 2>&1";

        gm::log_msg ("  [Example2] command: $cmd");

        $output = array ();
        $retval = 0;
        exec ($cmd, $output, $retval);

        $output = join ("\n", $output);
        gm::log_msg ("  [Example2] output: " . $output);

        $result = ($retval == 0) ? true : false;

        return array ('result' => $result, 'output' => $output);
    }
}
