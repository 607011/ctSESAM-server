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

$res = array('status' => 'ok');

$DB_PERSISTENT = true;
$DB_PATH = '/var/www/sqlite';
$DB_NAME = "$DB_PATH/ctSESAM-server-dev.sqlite";
#$DB_HOST = 'localhost';
#$DB_NAME = 'sesam';
#$DB_USER = 'sesam';
#$DB_PASSWD = 'sesam';
$DB_CONN = "sqlite:$DB_NAME";
#$DB_CONN = 'mysql:host='.$DB_HOST.';dbname='.$DB_NAME;

function directCall() {
  return substr(str_replace("\\", '/', __FILE__), -strlen($_SERVER['PHP_SELF'])) === $_SERVER['PHP_SELF'];
}

if (directCall()) {
  header('Content-Type: text/json');
  $res['message'] = "Calling " . $_SERVER['PHP_SELF'] . " directly doesn't do anything ;-)";
  echo json_encode($res);
}
?>
