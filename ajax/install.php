<?php
require_once 'globals.php';

header('Content-type: text/plain');
if ($dbh) {
    $dbh->exec('CREATE TABLE IF NOT EXISTS domains (' .
        ' userid VARCHAR(255) PRIMARY KEY,' .
        ' data BLOB' .
        ')');
    echo "Table 'domains' created.";
}
else {
  echo "Something went wrong.";
}
?>