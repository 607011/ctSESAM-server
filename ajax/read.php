<?php
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