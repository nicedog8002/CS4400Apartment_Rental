<?php
// The keys of the $_POST object are based on the "name" attribute
// of the HTML forms which submit to this page
$Username = $_POST['username'];
$Password = $_POST['password'];
if ($_POST['submit']) {
	// db() is a custom function written to abstract PHP queries
		//$query = "select * from User";
		$query = "SELECT Username 
				  FROM User AS U
                  WHERE EXISTS (
                  SELECT * FROM Management WHERE Management.Username = '$Username'
                   UNION ALL
				  SELECT * FROM Resident WHERE Resident.Username = '$Username' AND Apt_No IS NOT NULL) 
				  AND U.Username = '$Username' AND U.Password = '$Password';";
	    //echo $query;
		$result = db()->numOfRows($query);
		if (!$result) {
			$_SESSION['error'] = "Either the username of password is wrong";
			// $_SESSION['error'] = "An error occurred. " . db()->error();
		} else {
			// Registration was successful
			// Not secure but for now we can just save username in a session variable.
			$_SESSION['username'] = $Username;
			$_SESSION['notice'] = "You have successfully logged in. ";

			//Send the user to the home page
			redirect('home');
			exit;
		}
}
?>