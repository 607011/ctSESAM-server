<?php
require_once 'config.php';

function DEBUG($msg) {
    $timestamp = date('D M j H:i:s.u Y');
    file_put_contents('php://stdout', "[$timestamp] [ctlon:debug] $msg\n");
}

    


$T0 = microtime(true);
function processingTime() {
    global $T0;
    $dt = round(microtime(true) - $T0, 3);
    return ($dt < 0.001) ? '<1ms' : '~' . $dt . 's';
}

$dbh = new PDO("sqlite:$DB_NAME", null, null, array(
     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
     PDO::ATTR_PERSISTENT => $DB_PERSISTENT
));

?>
