<?php 
// The keys of the $_POST object are based on the "name" attribute
// of the HTML forms which submit to this page
$Username = $_SESSION['username'];
$Card_No = $_POST['card'];
$CVV = $_POST['cvv'];
$Name = $_POST['name'];
$Exp_Date = $_POST['expyear'] . $_POST['expmonth'];

if ($_POST['add']) {
	if (!$Username || !$Card_No || !$CVV || !$Name) {
		$_SESSION['error'] = "All fields are required for adding a card.";
	} else {
		// db() is a custom function written to abstract PHP queries
		$query = "INSERT INTO Payment_Information (Card_No, CVV, Name_On_Card, Exp_Date, Username) 
							VALUES ('$Card_No', '$CVV', '$Name', '$Exp_Date', '$Username')";
		if (db()->query($query)) {
			$_SESSION['notice'] = "You added a new card! ";
			redirect('payment');
			exit; 
		} else {
			$_SESSION['error'] = "You already have a card with that number. ";
			redirect('payment');
			exit; 
		}
	}
} else if ($_POST['delete']) {
	$query = "DELETE FROM Payment_Information 
							WHERE Card_No = '$Card_No' AND Username = '$Username'";
	if (db()->numOfRows($query) > 0) {
		$_SESSION['notice'] = "You successfully deleted a card! ";
		redirect('payment');
		exit; 
	} else {
		$_SESSION['error'] = "No card exists with the information selected. ";
		redirect('payment');
		exit; 
	}
}
?>