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

require_once 'globals.php';

if (!$dbh) {
    $res['status'] = 'error';
    $res['error'] = 'Connecting to database failed';
    goto end;
}

if (!isset($_REQUEST['data'])) {
    $res['status'] = 'error';
    $res['error'] = '"data" missing or invalid';
    goto end;
}

$domain = $_REQUEST['data'];

try {
    $domain = json_decode($domain, true);
}
catch (Exception $e) {
    $res['status'] = 'error';
    $res['error'] = $e->getMessage();
    goto end;
}

$res['domain'] = $domain;

$sql = 'DELETE FROM `domains` WHERE `name` = :name';
$sth = $dbh->prepare($sql);
$sth->bindParam(':name', $domain['name']);
try {
	$result = $sth->execute();
}
catch (PDOException $e) {
	$res['status'] = 'error';
	$res['error'] = $e->getMessage();
	$res['data'] = $data;
}
$res['$result'] = $result;
$res['rowcount'] = $sth->rowCount();
$res['sql'] = $sql;

end:
if (isset($res['status']) && $res['status'] === 'error') {
    $res['inserted'] = 0;
}

header('Content-Type: text/json');
echo json_encode($res);

?>