<?php defined('SESAM') or die('Direct access permitted');
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

$authenticated_user = null;

require_once '../lib/functions.php';

if (!file_exists('config.php')) {
    sendResponse(array(
        'message' => 'You need to copy ajax/config-default.php to ajax/config.php & open /ajax/install.php!',
        false
    ));
}
$config = require 'config.php';

$dbh = new PDO('sqlite:' . $config['db_path'] . '/' . $config['db_name'], null, null,
    array(
        PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => $config['db_persistent']
    )
);
