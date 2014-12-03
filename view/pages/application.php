<?php
$today = date("M j, Y");
$twoMonthFromNow = date("M j, Y");

?>
<h2>Prospective Resident Application Form</h2>
	<form id="application" action="application" method="post">
		<table class="form">
			<tr>
				<th><label for="name">Name</label></th>
				<td><input type="text" id="name" name="name"/></td>
			</tr>
			<tr>	
				<th><label for="dateOfBirth">Date of Birth</label></th>
				<td>
					<select name="birthyear">
					<?php 
					$maxYear = intval(date('Y')) - 10;
					for ($i = 0; $i < 120; $i++) {
						$year = $maxYear - $i;
						echo '
						<option value="' . $year . '">' . $year . '</option>';
					}
					?>
					</select>
					<select name="birthmonth">
						<option value="01">January</option>
						<option value="02">February</option>
						<option value="03">March</option>
						<option value="04">April</option>
						<option value="05">May</option>
						<option value="06">June</option>
						<option value="07">July</option>
						<option value="08">August</option>
						<option value="09">September</option>
						<option value="10">October</option>
						<option value="11">November</option>
						<option value="12">December</option>
					</select>
					<select name="birthday">
					<?php 
					for ($i = 1; $i <= 31; $i++) {

						echo '
						<option value="' . ($i < 10 ? '0' : '') . $i . '">' . $i . '</option>';
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="gender">Gender</label></th>
				<td>
					<input type="radio" name="gender" id="male" value="male" /> <label for="male">Male</label> 
					<input type="radio" name="gender" id="female" value="female" /> <label for="female">Female</label> 
				</td>
			</tr>
			<tr>
				<th><label for="income">Monthly Income</label></th>
				<td><input type="text" id="income" name="income" placeholder = "suggest 3 times more than the minimum rent"/></td>
			</tr>
			<tr>
				<th><label for="category">Category of Apartment</label></th>
				<td>
					<select id="category" name="category">
						<?php
							$query = "SELECT DISTINCT Category FROM apartment AS A
 										WHERE A.Apt_No NOT IN (SELECT Apt_No FROM Resident)
 										AND A.Category IS NOT NULL";
							$Categories = db()->query($query);
							echo $query;
							foreach ($Categories as $cat) {
								echo '<option value="' . $cat['Category'] . '">' . $cat['Category'] . '</option>';
							}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="maxrent">Max Rent</label></th>
				<td><input type="text" id="maxrent" name="maxrent" /></td>
			</tr>

			<tr>
				<th><label for="minrent">Min Rent</label></th>
				<td><input type="text" id="minrent" name="minrent"/></td>
			</tr>
			<tr>
				<th><label for="prefMoveDate">Preferred Move-In Date</label></th>
				<td><input type="text" id="prefMoveDate" name="movein" placeholder="between today and two months from now" /></td>
			</tr>
			<tr>
				<th><label for="lease">Lease Term</label></th>
				<td>
					<select id="lease" name="lease">
						<option value="3">3 MONTHS</option>
						<option value="6">6 MONTHS</option>
						<option value="12">12 MONTHS</option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="prev_residence">Previous Residence Address</label></th>
				<td><textarea name="prev_residence" id = "prev_residence"></textarea></td>
			</tr>
			<tr class="submit">
				<td></td>
				<td>
					<input type="submit" value="Submit Application" name="submit" />
				</td>
			</tr>
		</table>
	</form>