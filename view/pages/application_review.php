<?php 
$query = "(SELECT *, 0 AS Is_Accepted FROM Prospective_Resident AS P 
			WHERE NOT EXISTS 
			(SELECT Username FROM Resident AS R 
			WHERE R.Username = P.Username))
			UNION 
			(SELECT *, 1 AS Is_Accepted FROM Prospective_Resident AS P 
			WHERE EXISTS 
			(SELECT Username FROM Resident AS R 
			WHERE R.Username = P.Username AND Apt_No IS NULL))";
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
	echo "
	<tr>
		<td>$app[Name]</td>
		<td>$app[Date_of_Birth]</td>
		<td>$app[Gender]</td>
		<td>$" . number_format($app['Monthly_Income']) . "</td>
		<td>$app[Req_Cat]</td>
		<td>$app[Pref_Move]</td>
		<td>$app[Pref_Lease_Term]</td>
		<td>$app[Is_Accepted]</td>
		<td>
			" . ($app['Is_Accepted'] ? 
				'<input type="radio" name="username" value="' . $app['Username'] . '" />' : '') 
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
