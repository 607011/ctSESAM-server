<?php
require_once __DIR__ . '/ajax/globals.php';

header('Content-type: text/plain');
if ($dbh) {
    $dbh->exec('
        CREATE TABLE IF NOT EXISTS `domains` (
          `userid` TEXT PRIMARY KEY,
          `data` BLOB
        )'
    );
    echo 'Table "domains" created.';
} else {
    echo 'Something went wrong.';
}
