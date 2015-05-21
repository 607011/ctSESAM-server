<?php
require_once 'globals.php';

if ($dbh) {
    $dbh->exec('CREATE TABLE IF NOT EXISTS `domains` (' .
        ' `id` INTEGER PRIMARY KEY AUTOINCREMENT,' .
        ' `name` TEXT NOT NULL,' .
        ' `username` TEXT,' .
        ' `useLowerCase` INTEGER NOT NULL,' .
        ' `useUpperCase` INTEGER NOT NULL,' .
        ' `useDigits` INTEGER NOT NULL,' .
        ' `useExtra` INTEGER NOT NULL,' .
        ' `useCustom` INTEGER NOT NULL,' .
        ' `avoidAmbiguous` INTEGER NOT NULL,' .
        ' `customCharacters` TEXT,' .
        ' `iterations` INTEGER NOT NULL,' .
        ' `length` INTEGER NOT NULL,' .
        ' `salt` TEXT NOT NULL,' .
        ' `cDate` TEXT NOT NULL,' .
        ' `mDate` TEXT' .
        ')');
    $dbh->exec('CREATE INDEX IF NOT EXISTS `domain_name` ON `domains` (`name`)');
    echo "Table 'domains' created.<br/>\n";
}
?>
