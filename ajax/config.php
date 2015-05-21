<?php

//////////////////////////////////
/// CONFIGURATION OPTIONS
//////////////////////////////////

$res = array('status' => 'ok');

if ($_SERVER['SERVER_NAME'] === 'localhost' && strpos($_SERVER['REQUEST_URI'], '/ctpwdgen-server') === 0) {
    $DB_PATH = 'D:/Developer/xampp';
}
else if ($_SERVER['SERVER_NAME'] === 'ersatzworld.net' && strpos($_SERVER['REQUEST_URI'], '/ct/ctpwdgen-server') === 0) {
    $DB_PATH = '/var/www/sqlite';
}

// set this option to true to enable persistent database connections; set to false for debugging
$DB_PERSISTENT = false;
$DB_NAME = "$DB_PATH/ctpwdgen-server.sqlite";
$CACERT_NAME = "$DB_PATH/cacert.pem";

if (substr(str_replace("\\", '/', __FILE__), -strlen($_SERVER['PHP_SELF'])) === $_SERVER['PHP_SELF']) {
    header('Content-Type: text/json');
    echo json_encode($res);
}

?>