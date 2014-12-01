<h2>Request Maintenance</h2>
	<form id="requestMaintenance" action="maintenance" method="post">
		<table class="form">
			<tr>
				<th><label for="apartmentno">Apartment No</label></th>
				<td><input type="text" id="apartmentno" name="apt_no" value="<?php 
					echo $_SESSION['apt_no'] ?>" /></td>
			</tr>
			<tr>
				<th><label for="issue">Issue</label></th>
				<td>
					<select name="issue" id="issue">
						<?php 
						$issues = db()->fetchMany("SELECT * FROM ISSUE");
						foreach ($issues as $issue) {
							echo '
							 <option value="' . $issue['Issue_Type']  . '">' . $issue['Issue_Type'] . '</option>';
						}
						?>
					</select>
				</td>
			</tr>

			<tr class="submit">
				<td></td>
				<td>
					<input type="submit" value="Submit Maintenance Request" name="submit" />
				</td>
			</tr>
		</table>
	</form>
<a href="home">
	Back to home
</a>