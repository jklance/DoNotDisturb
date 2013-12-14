<?php
$configs = parse_ini_file('default.ini.php', true);
if (!is_array($configs)) {
    die(header("Status: 501"));
}

$delayTime = $_GET['delay_time'];        // In minutes
if (!$delayTime) { 
    $delayTime = $configs['general']['default_timer'];
}

$timeFile = $configs['paths']['time_file'];
$endingTime = time();

$fh = fopen($timeFile, "w");
    $endingTime += $delayTime;
    fwrite($fh, $endingTime);
fclose($fh);

exit(header("Status: 200"));
