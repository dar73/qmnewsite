<?php
$_ENV = parse_ini_file(realpath(dirname(__FILE__) . "/../.env"));
$_ENV = !empty($_ENV) ? $_ENV : array();
// define('SITE_ADDRESS', 'http://localhost:84/quotemasters.com/');

// define('DOCROOT', 'C:/xampp/htdocs/quotemasters.com/');

// define('SITE_NAME', 'Quote Master');  // 
// define('DB_HOST', 'localhost');

// define('DB_USERNAME', 'root');

// define('DB_PASSWORD', '');

// define('DB_NAME', 'quote_master');  // */

//online details


define('SITE_ADDRESS', $_ENV['SITE_ADDRESS']);
define('DOCROOT', $_ENV['DOCROOT']);
define('SITE_NAME', $_ENV['SITE_NAME']);

define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USERNAME', $_ENV['DB_USERNAME']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
define('DB_NAME', $_ENV['DB_NAME']);

date_default_timezone_set($_ENV['APP_TIMEZONE']);

?>