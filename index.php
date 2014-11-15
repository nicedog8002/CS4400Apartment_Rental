<?php 
define('VALID_SITE', true);

session_start();

require_once('core/core.php');

// site()->name();
site()->view($_GET['p']);

?>