<?php
defined('SESAM') or die('Direct access not permitted');

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

return array(
    'db_engine'     => 'sqlite',
    'db_path'       => '/var/www/sqlite',
    'db_name'       => 'ctSESAM-server.sqlite',
    'db_persistent' => true,
#    'db_engine'     => 'mysql',
#    'db_host'       => 'localhost',
#    'db_name'       => 'sesam',
#    'db_user'       => 'sesam',
#    'db_passwd'     => 'sesam'
    /* Uncomment if not using .htaccess basic auth
    'users'         => array(
       // To generate the hash you can use
       // php -r 'echo password_hash("s3curePa5sw0rd!", PASSWORD_BCRYPT) . PHP_EOL;'
       // in a terminal
       'username' => '$2y$10$HaShEdPa5SwORdw1Ths0m3Sal1',
    ),
    */
);
