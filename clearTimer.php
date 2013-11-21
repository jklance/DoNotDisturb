<?php
$timeFile = "time_ending.txt";
unlink($timeFile);

exit(header("Status: 200"));
