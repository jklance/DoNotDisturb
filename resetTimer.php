<?php
$delayTime = $_GET['delay_time'];        // In minutes
if (!$delayTime) { 
    $delayTime = 30;
}

$timeFile = "time_ending.txt";
$endingTime = time();

$fh = fopen($timeFile, "w");
    $endingTime += $delayTime * 60;
    fwrite($fh, $endingTime);
fclose($fh);

exit(header("Status: 200"));
