<?php 
$query = "INSERT INTO Date 
		SELECT * FROM (SELECT $Month, $Year) AS newEntry
  		WHERE NOT EXISTS (SELECT * FROM Date
        WHERE Date.Month = $Month AND Date.Year = $Year)";
db()->query($query);
?>