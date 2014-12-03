<?php 
$scheduleDate = date('Y') . '-' . date('m') .  '' . '-' . date('d');
$query = "SELECT A.Rent AS Calculated_Rent, A.Apt_No, Card_No
	FROM Apartment AS A, Resident AS R, Prospective_Resident AS PR, Payment_Information AS PI
	WHERE R.Username = PR.Username 
	AND R.Username = PI.Username
	AND A.Apt_No = R.Apt_No 
	AND R.Username = '$Username'
	AND now() > Pref_Move
	AND now() <= '$scheduleDate'
	AND ((MONTH('$scheduleDate') = MONTH(Pref_Move) AND DAY('$scheduleDate') < 7)
	OR (MONTH(Pref_Move) < MONTH('$scheduleDate') AND DAY('$scheduleDate') <= 3)
	OR (MONTH('$scheduleDate') < MONTH('$RentDate') AND YEAR('$scheduleDate') <= YEAR('$RentDate'))
	) AND A.Apt_NO NOT IN (SELECT Apt_NO FROM Payment)

	UNION

	SELECT A.Rent + (DAY('$scheduleDate') - 3) * 50 AS Calculated_Rent, A.Apt_No, Card_No
	FROM Apartment AS A, Resident AS R, Prospective_Resident AS PR, Payment_Information AS PI
	WHERE R.Username = PR.Username 
	AND R.Username = PI.Username
	AND A.Apt_No = R.Apt_No 
	AND R.Username = '$Username'
	AND now() > Pref_Move
	AND MONTH(now()) > MONTH(Pref_Move)
	AND now() <= '$scheduleDate'
	AND DAY('$scheduleDate') > 3
	AND (MONTH('$scheduleDate') >= MONTH('$RentDate') AND YEAR('$scheduleDate') >= YEAR('$RentDate'))
	AND A.Apt_NO NOT IN (SELECT Apt_NO FROM Payment)

	UNION

	SELECT (DAY(Pref_Move) / 30) * A.Rent AS Calculated_Rent, A.Apt_No, Card_No
	FROM Apartment AS A, Resident AS R, Prospective_Resident AS PR, Payment_Information AS PI
	WHERE R.Username = PR.Username 
	AND R.Username = PI.Username
	AND A.Apt_No = R.Apt_No 
	AND R.Username = '$Username'
	AND now() > Pref_Move
	AND now() <= '$scheduleDate'
	AND MONTH('$scheduleDate') = MONTH(Pref_Move)
	AND YEAR('$scheduleDate') = YEAR(Pref_Move)
	AND DAY(Pref_Move) > 7
	AND A.Apt_NO NOT IN (SELECT Apt_NO FROM Payment)";

	$res = db()->fetch($query);
?>
<h2>Online Rent Payment</h2>
	<form id="payRent" action="rent" method="post">
		<table class="form">
			<tr>
				<th><label for="date">Payment Date</label></th>
				<td><input type="text" id="date" name="date" class="datepicker" value="<?php 
					echo $scheduleDate; ?>" /></td>
			</tr>
			<tr>
				<th><label for="apartmentno">Apartment No</label></th>
				<td><input type="text" id="apartmentno" name="apt_no" value="<?php 
					echo $_SESSION['apt_no']; ?>" /></td>
			</tr>
			<tr>
				<th><label for="rentmonth">Rent for Month</label></th>
				<td>
					<select name="rentyear">
					<?php 
					$minYear = intval(date('Y'));
					for ($i = 0; $i < 3; $i++) {
						$year = $minYear + $i;
						echo '
						<option value="' . $year . '">' . $year . '</option>';
					}
					?>
					</select>
					<select name="rentmonth">
						<option value="<?php echo date('n'); ?>"><?php echo date('F'); ?></option>
						<option value="1">January</option>
						<option value="2">February</option>
						<option value="3">March</option>
						<option value="4">April</option>
						<option value="5">May</option>
						<option value="6">June</option>
						<option value="7">July</option>
						<option value="8">August</option>
						<option value="9">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="amount">Amount Due</label></th>
				<td><input type="text" id="amount" name="amount" value="<?php echo $rent; ?>" /></td>
			</tr>
			<tr>
				<th><label for="card">Use Card</label></th>
				<td>
					<select name="card" id="card">
				<?php 
				$Username = $_SESSION['username'];
				$query = "SELECT Card_No FROM Payment_Information WHERE Username = '$Username'";
				$cards = db()->fetchMany($query);
				foreach ($cards as $card) {
					echo '
						<option value="' . $card['Card_No'] . '">' . $card['Card_No'] . '</option>';
				}
				?>
					</select>
				</td>
			</tr>

			<tr class="submit">
				<td></td>
				<td>
					<input type="submit" value="Submit Rent Payment" name="submit" />
				</td>
			</tr>
		</table>
	</form>
<a href="home">
	Back to home
</a>