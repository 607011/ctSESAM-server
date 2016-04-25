<?php
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

define('SESAM', true);
require_once __DIR__ . '/../lib/base.php';

assert_basic_auth();

$res = array();

if (!$dbh) {
    sendResponse(array(
        'error' => 'Connecting to database failed',
        false
    ));
}

if (!isset($_REQUEST['data'])) {
    sendResponse(array(
        'error' => '"data" missing or invalid',
        false
    ));
}

$data = str_replace(' ', '+', $_REQUEST['data']);

$sth = $dbh->prepare('SELECT * FROM `domains` WHERE `userid` = :userid');
$sth->bindParam(':userid', $authenticated_user, PDO::PARAM_STR);
$result = $sth->execute();
$rows = $sth->fetch(PDO::FETCH_NUM);

if ($rows) {
    $sql = 'UPDATE `domains` SET `data` = :data WHERE `userid` = :userid';
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':userid', $authenticated_user, PDO::PARAM_STR);
    $sth->bindParam(':data', $data, PDO::PARAM_LOB);
    try {
        $result = $sth->execute();
    } catch (PDOException $e) {
        sendResponse(array(
            'error' => $e->getMessage(),
            false
        ));
    }
    $res['result'] = $result;
    $res['rowsaffected'] = $sth->rowCount();
} else {
    $sql = 'INSERT INTO `domains` (userid, data) VALUES(:userid, :data)';
    $sth = $dbh->prepare($sql);
    $sth->bindParam(':userid', $authenticated_user, PDO::PARAM_STR);
    $sth->bindParam(':data', $data, PDO::PARAM_LOB);
    try {
        $result = $sth->execute();
    } catch (PDOException $e) {
        sendResponse(array(
            'error' => $e->getMessage(),
            false
        ));
    }
    $res['result'] = $result;
    $res['rowsaffected'] = $sth->rowCount();
}

sendResponse($res);
