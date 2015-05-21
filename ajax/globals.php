<?php
require_once 'config.php';

function DEBUG($msg) {
    $timestamp = date('D M j H:i:s.u Y');
    file_put_contents('php://stdout', "[$timestamp] [ctpwdgen:debug] $msg\n");
}

function assert_basic_auth()
{
	global $res;
	if (!isset($_SERVER['PHP_AUTH_USER'])) {
	    header('WWW-Authenticate: Basic realm="ctpwdgen sync server"');
	    header('HTTP/1.0 401 Unauthorized');
	    echo '<p>HTTP basic authentication required</p>';
	    exit;
	}
	else {
		$res['user'] = $_SERVER['PHP_AUTH_USER'];
		$res['pass'] = $_SERVER['PHP_AUTH_PW'];
	}
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
