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

require_once 'config.php';


$authenticated_user = null;


function DEBUG($msg) {
  $timestamp = date('D M j H:i:s.u Y');
  file_put_contents('php://stdout', "[$timestamp] [ctpwdgen:debug] $msg\n");
}


function assert_basic_auth() {
  global $authenticated_user;
  global $res;
  if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="ctpwdgen sync server"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'HTTP basic authentication required';
    exit;
  }
  else {
    $authenticated_user = $_SERVER['PHP_AUTH_USER'];
  }
}

$T0 = microtime(true);
function processingTime() {
  global $T0;
  $dt = round(microtime(true) - $T0, 3);
  return ($dt < 0.001) ? '<1ms' : '~' . $dt . 's';
}

$dbh = new PDO("sqlite:$DB_NAME", null, null,
	       array(
		     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		     PDO::ATTR_PERSISTENT => $DB_PERSISTENT
		     )
	       );

