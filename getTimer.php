<?php
$timeFile = "time_ending.txt";
$endingTime = time();
$timeLeft = 0;

if (file_exists($timeFile)) {
    $fh = fopen($timeFile, "r");
        $endingTime = fread($fh, filesize($timeFile));
    fclose($fh);
    $timeLeft = $endingTime - time();
}

if ($timeLeft < 0) {
        $timeLeft = 0;
}

header("Status: 200");
echo json_encode(array("time" => gmdate("i:s", $timeLeft)));
