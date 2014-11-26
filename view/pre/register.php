<?php 
// The keys of the $_POST object are based on the "name" attribute
// of the HTML forms which submit to this page
$Username = $_POST['username'];
$Password = $_POST['password'];
$Password2 = $_POST['password2'];

if ($_POST['submit']) {
	// db() is a custom function written to abstract PHP queries
	if ($Password == "" || $Username == "") {
		$_SESSION['error'] = "Please complete all fields. ";
	} else if ($Password2 != $Password) {
		$_SESSION['error'] = "Your passwords are different!";
	} else {
		$query = "INSERT INTO User (Username, Password) VALUES ('$Username', '$Password')";

		$result = db()->query($query);
		if (!$result) {
			$_SESSION['error'] = "The username you picked is already taken. ";
			// $_SESSION['error'] = "An error occurred. " . db()->error();
		} else {
			// Registration was successful

			// Not secure but for now we can just save username in a session variable.
			$_SESSION['username'] = $Username;
			$_SESSION['notice'] = "You have successfully registered. ";

			// Send the user to the application page
			redirect('application');
			exit;
		}
	}
}


?>