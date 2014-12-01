<?php 
if(!defined('VALID_SITE')) exit('No direct access! ');

require_once('./config/config.php');


require_once('functions.php');


include('site.class.php');


function site()
{
	return Site::single(); 
}

function db($key = 'default')
{
	return site()->db($key);
}

include('database.class.php');

include('table.class.php');
?>
