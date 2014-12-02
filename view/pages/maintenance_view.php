<form action="maintenance_view" method="post">
<table>
	<tr>
		<th>
			Date of Request
		</th>
		<th>
			Apt No
		</th>
		<th>
			Description of Issue
		</th>
		<td>
		</td>
	</tr>
<?php 
	$query = "SELECT * FROM Maintenance_Request 
						WHERE Issue_Status = 'unresolved'
						ORDER BY Date_Of_Request ASC";
	$requests = db()->fetchMany($query);
	foreach ($requests as $req) {
		echo "
		<tr>
			<td>
				$req[Date_Of_Request]
			</td>
			<td>
				$req[Apt_No]
			</td>
			<td>
				$req[Issue_Type]
			</td>
			<td>
				<input type='checkbox' name='requests[]' 
					value='$req[Date_Of_Request],$req[Apt_No],$req[Issue_Type]' />
			</td>
		</tr>";
	}
?>
	<tr>
		<td colspan="4">
			<input type="submit" value="Resolve Issue" name="submit" />
		</td>
	</tr>
</table>
</form>

<h3>Resolved Issues</h3>
<table>
	<tr>
		<th>
			Date of Request
		</th>
		<th>
			Apt No
		</th>
		<th>
			Description of Issue
		</th>
		<th>
			Date Resolved
		</th>
	</tr>
<?php 
	$query = "SELECT * FROM Maintenance_Request 
						WHERE Issue_Status = 'resolved'
						ORDER BY Date_Of_Request ASC";
	$requests = db()->fetchMany($query);
	foreach ($requests as $req) {
		echo "
		<tr>
			<td>
				$req[Date_Of_Request]
			</td>
			<td>
				$req[Apt_No]
			</td>
			<td>
				$req[Issue_Type]
			</td>
			<td>
				$req[Date_Resolved]
			</td>
		</tr>";
	}
?>
</table>
<br /><a href="home">Back to Home</a>