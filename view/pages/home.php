<?php 
if (!$_SESSION['is_manager']) {
?>
<h2>Manage Your Apartment</h2>
<div class="row">
	<h3>Residents</h3>
	<?php 
	$query = "SELECT COUNT(Date) AS Count FROM Reminder 
				WHERE Apt_No = (SELECT Apt_No FROM Resident 
					WHERE Username = '$Username') AND Status = 'unread'";
	
	$count = db()->fetch($query);
	$count = $count['Count'];
	?>
	<p>
		<a href="message">You have <?php echo number_format($count); ?> Messages
		</a>
	</p>
	<div>
		<a href="rent">Pay Rent</a>
		<a href="maintenance">Request Maintenance</a>
		<a href="payment">Payment Information</a>
	</div>
</div>
<?php 
} else {
?>
<h2>Manage Your Buildings</h2>
<div class="row">
	<h3>Management</h3>
	<div>
		<a href="application_review">Review Applications</a>
		<a href="maintenance">View Maintenance Requests</a>
		<a href="reminder">Send Rent Reminder</a>
		<a href="report">View Reports</a>
	</div>
</div>
<?php 
} 
?>