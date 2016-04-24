<?php
/*

    Copyright (c) 2015 Oliver Lau <ola@ct.de>, Heise Medien GmbH & Co. KG

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

$T0 = microtime(true);
$res = array('status' => 'ok');
$authenticated_user = null;

require_once '../lib/functions.php';
require_once 'config.php';

if (directCall()) {
    $res['message'] = 'Calling ' . $_SERVER['PHP_SELF'] . ' directly doesn\'t do anything ;-)';
    sendResponse($res);
}

$dbh = new PDO('sqlite:' . $DB_NAME, null, null,
    array(
        PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => $DB_PERSISTENT
    )
);
