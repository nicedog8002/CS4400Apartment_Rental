<?php 
$Username = $_SESSION['username'];
$query = "SELECT `Message`, `Date` FROM Reminder 
					WHERE Apt_No = (SELECT Apt_No FROM Resident WHERE Username = '$Username') 
					AND Status = 'unread' ORDER BY `Date` DESC";

$messages = db()->fetchMany($query);
?>

<table>
	<tr>
		<th>Date</th>
		<th>Message</th>
	</tr>
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
				$m[Date]
			</td>
			<td>
				$m[Message]
			</td>
		</tr>";

	$query = "UPDATE Reminder SET Status = 'read' 
			  WHERE  Apt_No = (SELECT Apt_No FROM Resident WHERE Username = '$Username') 
			  AND Status = 'unread' AND Date = $m[date]";
	echo $query;
	$result = db()->query($query);
	}
}
?>
</table>
<br>
<a href="home">Back to home</a>