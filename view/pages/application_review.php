<?php 
$query = "SELECT * FROM Prospective_Resident AS P 
			WHERE NOT EXISTS 
			(SELECT Username FROM Resident AS R 
			WHERE R.Username = P.Username)";

$apps = db()->fetchMany($query);
?>
<form action="allot_apartment" method="post" >
<table>
	<tr>
		<th>
			Name
		</th>
		<th>
			Date of Birth
		</th>
		<th>
			Gender
		</th>
		<th>
			Monthly Income ($)
		</th>
		<th>
			Type of Apartment Requested
		</th>
		<th>
			Preferred Move-In Date
		</th>
		<th>
			Lease Term
		</th>
		<th>
			Rejected / Accepted
		</th>
		<td></td>
	</tr>
<?php 
foreach ($apps as $app) {
	$Username = $app['Username'];
	$query = "SELECT Username 
						FROM Prospective_Resident AS P
							WHERE P.Username = '$Username'
								AND (P.Pref_Move >= now()) 
								AND (P.Pref_Move <= now() + INTERVAL 2 MONTH)
								AND EXISTS (SELECT Apt_No FROM Apartment AS A 
							WHERE A.Category = P.Req_Cat 
								AND A.Available_On <= P.Pref_Move 
								AND P.Monthly_Income >= 3*A.Rent 
								AND P.Min_Rent <= A.Rent 
								AND P.Max_Rent >= A.Rent) 
								AND NOT EXISTS (SELECT * FROM Resident WHERE Username = '$Username')";
	$Is_Accepted = db()->numOfRows($query);
	$Accepted = ($Is_Accepted ? 'Accepted' : 'Rejected');
	echo "
	<tr>
		<td>$app[Name]</td>
		<td>$app[Date_of_Birth]</td>
		<td>$app[Gender]</td>
		<td>$" . number_format($app['Monthly_Income']) . "</td>
		<td>$app[Req_Cat]</td>
		<td>$app[Pref_Move]</td>
		<td>$app[Pref_Lease_Term] Months</td>
		<td>$Accepted</td>
		<td>
			" . ($Is_Accepted ? 
				'<input type="radio" name="username" value="' . $Username . '" />' : '') 
			. "
		</td>
	</tr>";
}
?>
	<tr>
		<td colspan="9" style="text-align:center;">
			<input type="submit" value="Next" name="submit" />
		</td>
	</tr>
</table>
</form>
<br /><a href="home">Back to Home</a>
