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

assert_basic_auth();

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

$domain = $_REQUEST['domain'];
$data = $_REQUEST['data'];

$res['domain'] = $domain;

$sth = $dbh->prepare('SELECT * FROM `domains` WHERE `userid` = :userid AND `domain` = :domain');
$sth->bindParam(':domain', $domain);
$sth->bindParam(':userid', $authenticated_user);
$result = $sth->execute();
$rows = $sth->fetch(PDO::FETCH_NUM);
if ($rows) {
	$sql = 'UPDATE `domains` SET' .
        ' `data` = :data,' .
        ' WHERE `userid` = :userid AND `domain` = :domain';
	$sth = $dbh->prepare($sql);
    $sth->bindParam(':domain', $domain);
    $sth->bindParam(':userid', $authenticated_user]);
    $sth->bindParam(':data', $data);
    try {
    	$result = $sth->execute();
    }
    catch (PDOException $e) {
    	$res['status'] = 'error';
    	$res['error'] = $e->getMessage();
    	$res['data'] = $data;
    }
    $res['result'] = $result;
    $res['rowcount'] = $sth->rowCount();
}
else {
	$sql = 'INSERT INTO `domains` (domain, userid, data) VALUES(:domain, :userid, :data)';
	$sth = $dbh->prepare($sql);
    $sth->bindParam(':domain', $domain);
    $sth->bindParam(':userid', $authenticated_user]);
    $sth->bindParam(':data', $data);
    try {
    	$result = $sth->execute();
    }
    catch (PDOException $e) {
    	$res['status'] = 'error';
    	$res['error'] = $e->getMessage();
    	$res['data'] = $domain;
    }
    $res['result'] = $result;
    $res['rowcount'] = $sth->rowCount();
}


end:
if (isset($res['status']) && $res['status'] === 'error') {
    $res['inserted'] = 0;
}

header('Content-Type: text/json');
echo json_encode($res);

?>