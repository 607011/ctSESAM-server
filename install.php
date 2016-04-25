<?php
define('SESAM', true);
require_once __DIR__ . '/lib/base.php';

header('Content-type: text/plain');
if ($dbh) {
    $dbh->exec('
        CREATE TABLE IF NOT EXISTS `domains` (
          `userid` TEXT PRIMARY KEY,
          `data` BLOB
        )'
    );
    if ($config['db_engine'] === 'sqlite') {
        echo 'Table "domains" created in ' . $config['db_engine'] . ' database @ ' . $config['db_path'] . '/' . $config['db_name'];
    }
    else {
        echo 'Table "domains" created in ' . $config['db_engine'] . ' database @ ' . $config['db_host'] . '/' . $config['db_name'];
    }
}
else {
    echo 'Something went wrong.';
}
