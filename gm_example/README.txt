gm_worker1.php/gm_client1.php

  The most basic example; uses job class Example1

gm_worker2.php/gm_client2.php

  Illustrates a task set.

gm_worker3.php/gm_client3.php

  Adds a "complete" callback to the client

gm_worker4.php/gm_client4.php

  Job class Example2 returns an array, illustrating how to send
  back data along with a boolean status.

gm_worker5.php/gm_client5.php

  Added "fail" callback to client

gm_worker6.php/gm_client6.php

  Added monitor callback to worker

gm_worker7.php/gm_client7.php

  Added "start", "complete", "fail" worker callbacks

gm_worker8.php/gm_client8.php

  added options to worker to govern the number of jobs processed before
  exiting; added mutex to prevent multiple instances from running (so that
  you can use a frequent cron startup to keep the process up, even if
  it is exiting after a specified number of jobs)

