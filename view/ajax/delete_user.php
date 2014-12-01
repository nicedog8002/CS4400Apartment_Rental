<?php 
if ($_SESSION['username'] && $_SESSION['not_applied']) {
	$Username = $_SESSION['username'];
	$query = "DELETE FROM User WHERE Username = '$Username' 
				AND NOT EXISTS (SELECT Username FROM Prospective_Resident AS P 
					WHERE User.Username = P.Username)";
	if (db()->numOfRows($query) > 0) {
		$_SESSION['error'] = "Your account has been deleted because you did not complete a 
													prospective resident form. You will need to reregister to apply. ";
	} else {
		$_SESSION['error'] = "Invalid deletion attempt. " . db()->error();
	}
} else {
	$_SESSION['error'] = "Can't delete a user who's not currently applying. ";
}
?>