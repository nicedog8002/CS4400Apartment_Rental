<?php 
if ($_SESSION['username'] == $_GET['username'] && $_SESSION['not_applied']) {
	$query = "DELETE FROM User WHERE Username = '$Username' 
				AND NOT EXISTS (SELECT Username FROM Prospective_Resident AS P 
				WHERE User.Username = P.Username";
	if (db()->numOfRows($query) > 0) {
		echo "Your account has been deleted because you did not complete a prospective resident form. ";
	} else {
		echo "Invalid deletion attempt. ";
	}
} else {
	echo "Invalid deletion attempt. ";
}
?>