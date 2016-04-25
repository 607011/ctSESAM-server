<?php
defined('SESAM') or die('Direct access not permitted');

/*

    Copyright (c) 2015-2016 Oliver Lau <ola@ct.de>, Heise Medien GmbH & Co. KG

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

$authenticated_user = null;

require_once __DIR__ . '/functions.php';

if (!file_exists(__DIR__ . '/../config.php')) {
    sendResponse(array(
        'error' => 'You have to copy /config-default.php to /config.php & open /install.php!',
        false
    ));
}
$config = require __DIR__ . '/../config.php';

$dbh = null;
if ($config['db_engine'] === 'sqlite') {
    $dbh = new PDO('sqlite:' . $config['db_path'] . '/' . $config['db_name'], null, null,
        array(
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => $config['db_persistent']
        )
    );
}
else {
    $dbh = new PDO($config['db_engine'] . ':host=' . $config['db_host'] . ';dbname=' . $config['db_name'], $config['db_user'], $config['db_passwd'],
        array(
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => $config['db_persistent']
        )
    );
}
