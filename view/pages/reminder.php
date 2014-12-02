<h2>Reminder</h2>

<div align ="center"> 
	Date: <?php echo date("M j, Y"); ?>
</div>
<?php 
	$day = date('j');
if ($day > 0) {
?>
	<form id="Reminder" action="reminder" method="post">
		<table class="form">
			<?php 
				$query =  "SELECT A.Apt_No AS apt_no FROM Apartment AS A 
						   INNER JOIN Resident AS R ON A.Apt_No = R.Apt_No 
						   WHERE A.Apt_No NOT IN 
						   (SELECT P.Apt_No FROM payment AS P 
							WHERE P.Month = Month(now()) AND P.Year = Year(now()));";

				$result = db()->query($query);
				echo "<select name='apt_no'>";
				echo '<option value="">' . "Defaulted Apartments" . "</option>\n";
				while($row = $result->fetch_assoc()) {
	    			echo '<option value="'. $row['apt_no'] . '">' . $row['apt_no'] . "</option>\n";
				}
				echo "</select>";
			?>
			<div>
				<div><p><b>Message: </b></p></div>
				<div>
					<p> Your payment is past due. Please pay immediately. </p>
				</div>
			</div>
			<tr class="submit">
				<td>
					<input type="submit" value="Send" name = "submit"/>
				</td>
			</tr>
		</table>
	</form>
	<?php
	$selected_apt_no = $_POST['apt_no'];
	if ($_POST['submit']) {

		$query = "INSERT INTO Reminder (Date, Apt_No, Message, Status) 
				  VALUES (now(), '$selected_apt_no', 'Your payment is past due. Please pay immediately. ', 'unread');";
		//echo $query;

		$result = db()->query($query);
		if (!$result) {
			echo "Didn't process successfully or reminder has sent to this Apartment already today!";
			echo '<meta http-equiv="refresh" content="2; reminder" />';
			//redirect('reminder');

			//$_SESSION['error'] = "An error occurred. " . db()->error();
		} else {
			echo "You have successfully sent the reminder! ";
			echo '<meta http-equiv="refresh" content="2; reminder" />';
			//redirect('reminder');
		}
	}
	?>
<?php 
}
?>

<br>
<a href="home">
  Back to home
</a>
