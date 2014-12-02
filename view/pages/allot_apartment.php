<?php 
$Username = $_SESSION['username'];
$query = "SELECT * FROM Apartment AS A, Prospective_Resident AS P 
      WHERE P.Username = $Username 
      AND A.Available_On <= P.Pref_Move 
      AND A.Category = P.Req_Cat 
      AND A.Rent <= P.Max_Rent
      AND A.Rent >= P.Min_Rent
      AND A.Apt_No NOT IN (SELECT DISTINCT Apt_No FROM RESIDENT)";
$apartments = db()->fetchMany($query);
?>
<table>
	<tr>
		<th>
			Apartment No
		</th>
		<th>
			Category
		</th>
		<th>
			Monthly Rent ($)
		</th>
		<th>
			Sq Ft.
		</th>
		<th>
			Available From
		</th>
		<th>
		</th>
	</tr>
<?php 
	foreach ($apartments as $a) {
		echo "
	<tr>
		<td>
			$a[Apt_No]
		</td>
		<td>
			$a[Category]
		</td>
		<td>
			" . number_format($a['Rent']) . "
		</td>
		<td>
			" . number_format($a['Square_Feet']) . "
		</td>
		<td>
			$a[Available_On]
		</td>
		<td>
			<input type='radio' name='apt_no' value='$a[Apt_No]' />
		</td>
	</tr>
		";
	}
?>
	<tr>
		<td colspan="6">
			<input type="submit" value="Allot Apartment" name="submit" />
		</td>
	</tr>
</table>

<br /><a href="application_review">Pick a different user</a>