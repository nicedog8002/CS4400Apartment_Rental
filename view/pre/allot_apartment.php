<?php 
if (!($Username = $_POST['username'])) {
	$_SESSION['error'] = 'No user selected! ';
	redirect('application_review');
	exit;
} else if ($Apt_No = $_POST['apt_no']) {
	$query1 = "INSERT INTO Resident (Apt_No, Username) VALUES ($Apt_No, '$Username')";
	$query2 = "UPDATE Apartment AS A
		SET A.Available_On = (SELECT Pref_Move + INTERVAL Pref_Lease_Term MONTH 
		FROM Prospective_Resident WHERE Username = '$Username'), 
			A.Lease_Term  = (SELECT Pref_Lease_Term FROM Prospective_Resident 
				WHERE Username = '$Username')
		WHERE A.Apt_No = $Apt_No";
	if (db()->query($query1) && db()->query($query2)) {
		$_SESSION['notice'] = "You've successfuly allotted an apartment to $Username. ";
		redirect('application_review');
		exit;
	} else {
		$_SESSION['error'] = "Updating the apartment data failed. " . db()->error();
	}
} else if ($_POST['allot']) {
	$_SESSION['error'] = "No apartment selected!";
}
?>