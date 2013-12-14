<?php
$configs = parse_ini_file('default.ini.php', true);
if (!is_array($configs)) {
    die(header("Status: 501"));
}

$timeFile = $configs['paths']['time_file'];
if (file_exists($timeFile)) {
    unlink($timeFile);
}

exit(header("Status: 200"));
