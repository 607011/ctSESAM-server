<?php
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