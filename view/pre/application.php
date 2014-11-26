<?php 
$Categories = db()->fetchMany("SELECT DISTINCT Category FROM Apartment");

?>