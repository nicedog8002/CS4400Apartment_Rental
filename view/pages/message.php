<?php 
$query = "SELECT `Message`, `Date` FROM Reminder 
					WHERE Apt_No = (SELECT Apt_No FROM Resident WHERE Username = '$Username') 
					AND Status = 'unread' ORDER BY `Date` DESC";

$messages = db()->fetchMany($query);
?>

<table>
<?php
if (count($messages) < 1) {
?>
	<tr>
		<td colspan="2">
			You have no messages! 
		</td>
	</tr>
<?php 
} else {
	foreach ($messages as $m) {
		echo "
		<tr>
			<td>
				$m[date]
			</td>
			<td>
				$m[message]
			</td>
		</tr>";
	}
}
?>
</table>

<br>
<a href="home">Back to home</a>