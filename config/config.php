<?php 
if(!defined('VALID_SITE')) exit('No direct access! ');

date_default_timezone_set('America/New_York');
error_reporting(0);

### Constants and vars ###
define('DOMAIN', 'http://localhost:8080');
define('COOKIE_DOMAIN', '');

define('SITE_NAME', 'Apartment Manager');
define('SITE_DESCRIPTION', 'A CS 4400 Project ');

define('SITE_PATH', '/CS4400Apartment_Rental/');
define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT'] . SITE_PATH);

define('ENCRYPTION_KEY', 'Rb[@SLxL?H#+LXl]&OyOw[[@qmBO(D&Zzs^~bRrvQ_8R+yER<T)CP^W)Ht(DU"nomu<Z1SwLqC6[0C?Zr:8"I#nho');

### Database information ###
global $db_settings; 
$db_settings['default']['host'] =  'localhost';
$db_settings['default']['db'] = '';
$db_settings['default']['user'] = '';
$db_settings['default']['pass'] = '';

?>