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

if (isset($_REQUEST['what'])) {
	if ($_REQUEST['what'] === 'all') {
		$sth = $dbh->prepare('SELECT `name`, `username`, `useLowerCase`, `useUpperCase`, `useDigits`, `useExtra`, `useCustom`, `avoidAmbiguous`, `customCharacters`, `iterations`, `length`, `salt`, `cDate`, `mDate` FROM `domains`');
		$result = $sth->execute();
		$res['result'] = $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	else if ($_REQUEST['what'] === 'single' && isset($_REQUEST['domain'])) {
		$sth = $dbh->prepare('SELECT `name`, `username`, `useLowerCase`, `useUpperCase`, `useDigits`, `useExtra`, `useCustom`, `avoidAmbiguous`, `customCharacters`, `iterations`, `length`, `salt`, `cDate`, `mDate` FROM `domains` WHERE `name` = :name');
		$sth->bindParam(':name', $_REQUEST['domain']);
		$result = $sth->execute();
		$res['result'] = $sth->fetchAll(PDO::FETCH_ASSOC);
	}
}

end:
header('Content-Type: text/json');
echo json_encode($res);

?>