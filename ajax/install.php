<?php
require_once 'globals.php';

if ($dbh) {
    $dbh->exec('CREATE TABLE IF NOT EXISTS `domains` (' .
        ' `id` INTEGER PRIMARY KEY AUTOINCREMENT,' .
        ' `domain` TEXT NOT NULL,' .
        ' `userid` TEXT NOT NULL,' .
        ' `data` BLOB' .
        ')');
    $dbh->exec('CREATE INDEX IF NOT EXISTS `domain_user` ON `domains` (`userid`, `domain`)');
    echo "Table 'domains' created.<br/>\n";
}
?>
