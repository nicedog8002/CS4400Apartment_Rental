<?php 
if (!($Username = $_POST['username'])) {
	$_SESSION['error'] = 'No user selected! ';
	redirect('application_review');
	exit;
}

if ($Apt_No = $_POST['apt_no']) {
	$query1 = "UPDATE Apartment AS A
		SET A.Available_On = (SELECT Pref_Move + INTERVAL Pref_Lease_Term MONTH 
		FROM Prospective_Resident WHERE Username = '$Username') 
		WHERE A.Apt_No = $Apt_No";

	$query2 = "UPDATE Resident SET Apt_No = $Apt_No WHERE Username = '$Username'";
	if (db()->query($query1) && db()->query($query2)) {
		$_SESSION['notice'] = "You've successfuly allotted an apartment to $Username. ";
		redirect('application_review');
		exit;
	} else {
		$_SESSION['error'] = "Updating the apartment data failed. " . db()->error();
	}
} else {
	$_SESSION['error'] = "No apartment selected!";
}
?>