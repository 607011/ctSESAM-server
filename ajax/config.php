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

if ($_SERVER['SERVER_NAME'] === 'localhost' && strpos($_SERVER['REQUEST_URI'], '/ctSESAM') === 0) {
  $DB_PATH = 'D:/Developer/xampp';
}
else if ($_SERVER['SERVER_NAME'] === 'ersatzworld.net' && strpos($_SERVER['REQUEST_URI'], '/ctSESAM') === 0) {
  $DB_PATH = '/var/www/sqlite';
}

// set this option to true to enable persistent database connections; set to false for debugging
$DB_PERSISTENT = false;
$DB_NAME = "$DB_PATH/ctSESAM-server.sqlite";

function directCall() {
  return substr(str_replace("\\", '/', __FILE__), -strlen($_SERVER['PHP_SELF'])) === $_SERVER['PHP_SELF'];
}

if (directCall()) {
  header('Content-Type: text/json');
  $res['message'] = "Calling " . $_SERVER['PHP_SELF'] . " directly doesn't do anything ;-)";
  echo json_encode($res);
}
