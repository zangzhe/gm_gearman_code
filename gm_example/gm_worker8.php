#!/usr/bin/php -q
<?php

require_once ('gm_shared.php');
require_once ('Net/Gearman/Worker.php');

process_options ();

gm::log_msg ("[gm_worker] starting instance $g_instance_num...");

if (!get_lock ($g_instance_num))
{
    exit;
}

$g_num_jobs_run = 0;

try
{
    gm::log_msg ("[gm_worker] starting worker...");
    $worker = new Net_Gearman_Worker (gm::$servers);
    $worker->addAbility ('Example2');

    $worker->attachCallback ('job_start', Net_Gearman_Worker::JOB_START);
    $worker->attachCallback ('job_complete', Net_Gearman_Worker::JOB_COMPLETE);
    $worker->attachCallback ('job_fail', Net_Gearman_Worker::JOB_FAIL);

    $worker->beginWork ('monitor');

    release_lock ();
    gm::log_msg ("[gm_worker] lock released; shutting down...");
}
catch (Net_Gearman_Exception $e)
{
    gm::log_msg ("[gm_worker] exception caught: " .  $e->getMessage());
}

function monitor ($idle, $time_of_last_job)
{
    global $g_num_jobs_to_run, $g_num_jobs_run;
    
    $idle_str = ($idle) ? 'idle' : 'not idle';
    $time_of_last_job_str = date ('r', $time_of_last_job);
    gm::log_msg ("[gm_worker] status: $idle_str, time of last job: $time_of_last_job_str");

    if ($g_num_jobs_to_run > 0)
    {
        if ($g_num_jobs_run >= $g_num_jobs_to_run)
        {
            gm::log_msg ("[gm_worker] completed $g_num_jobs_run jobs");

            // return true to stop processing jobs
            return true;
        }
    }

    // return false to keep processing jobs...
    return false;
}   

function job_start ($handle, $job, $args)
{
    gm::log_msg ("[gm_worker] job ($handle/$job) started");
}

function job_complete ($handle, $job, $result)
{
    global $g_num_jobs_run;

    gm::log_msg ("[gm_worker] job ($handle/$job) complete");

    $g_num_jobs_run++;
}

function job_fail ($handle, $job, $error)
{
    global $g_num_jobs_run;

    gm::log_msg ("[gm_worker] job ($handle/$job) failed: $error");

    $g_num_jobs_run++;
}


function release_lock ()
{
    global $g_lock_fp;
    fclose ($g_lock_fp);
}


function get_lock ($instance_num)
{
    global $g_lock_fp;

    if (!($g_lock_fp = fopen("/tmp/gm_worker_$instance_num.txt", "w")))
    {
        return false;
    }

    if (!flock($g_lock_fp, LOCK_EX | LOCK_NB))
    {
        return false;
    }

    return true;
}


function process_options ()
{
    global $g_instance_num, $g_num_jobs_to_run;

    $opt = getopt('i:n:h');

    if (isset ($opt['i']) && !is_numeric ($opt['i']))
    {
        print_usage ();
        exit;
    }
    if (isset ($opt['n']) && !is_numeric ($opt['n']))
    {
        print_usage ();
        exit;
    }

    if (isset ($opt['h']))
    {
        print_usage ();
        exit;
    }

    $g_instance_num = 1;
    if (isset ($opt['i']))
    {
        $g_instance_num = $opt['i'];
    }

    $g_num_jobs_to_run = 0;
    if (isset ($opt['n']))
    {
        $g_num_jobs_to_run = $opt['n'];
    }
}


function print_usage ()
{
    global $argv;

    print <<<__TEXT__
Usage: {$argv[0]} [-i INSTANCE] [-n NUMJOBS] [-h]
    -i INSTANCE   the instance number
    -n NUMJOBS    number of jobs to run before exiting
    -h            print this help message

Only one copy of this script will run with each instance number.  This allows
you to set up a cron job like this, for example:


* * * * * /path/to/gm_worker.php -i 1 -n 10

If an "instance 1" process is still running when the cron job fires off another
copy, the new process will exit.


__TEXT__;
}

