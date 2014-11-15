<h2>Online Rent Payment</h2>
	<form id="payRent" action="rent" method="post">
		<table class="form">
			<tr>
				<th><label for="date">Payment Date</label></th>
				<td><input type="text" id="date" name="date" class="datepicker" /></td>
			</tr>
			<tr>
				<th><label for="apartmentno">Apartment No</label></th>
				<td><input type="text" id="apartmentno" name="apartmentno" /></td>
			</tr>
			<tr>
				<th><label for="rentmonth">Rent for Month</label></th>
				<td>
					<select name="rentyear">
					<?php 
					$maxYear = intval(date('Y'));
					for ($i = 0; $i < 10; $i++) {
						$year = $maxYear - $i;
						echo '
						<option value="' . $year . '">' . $year . '</option>';
					}
					?>
					</select>
					<select name="rentmonth">
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
				<td><input type="text" id="amount" name="amount" /></td>
			</tr>
			<tr>
				<th><label for="card">Use Card</label></th>
				<td>
					<select name="card" id="card"></select>
				</td>
			</tr>

			<tr class="submit">
				<td></td>
				<td>
					<input type="submit" value="Submit Rent Payment" />
				</td>
			</tr>
		</table>
	</form>
<a href="home">
	Back to home
</a>