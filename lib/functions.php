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

function sendResponse($data = array(), $success = true)
{
    $status = 'ok';

    if (!$success) {
        $status = 'error';
    }
    
    $data = array_merge(array(
        'status' => $status
    ), $data);

    header('Content-Type: text/json');
    $json = json_encode($data, JSON_UNESCAPED_SLASHES);
    echo $json;
    exit;
}

function assert_basic_auth()
{
    global $authenticated_user;
    if (preg_match('/Basic\s+(.*)$/i', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
        list($name, $password) = explode(':', base64_decode($matches[1]));
        $_SERVER['PHP_AUTH_USER'] = strip_tags($name);
        $_SERVER['PHP_AUTH_PW'] = strip_tags($password);
    }
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        header('WWW-Authenticate: Basic realm="ctSESAM sync server"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'HTTP basic authentication required';
        exit;
    } else {
        $authenticated_user = $_SERVER['PHP_AUTH_USER'];
    }
}
