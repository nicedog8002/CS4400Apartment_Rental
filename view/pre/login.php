<?php
// The keys of the $_POST object are based on the "name" attribute
// of the HTML forms which submit to this page
$Username = $_POST['username'];
$Password = $_POST['password'];
if ($_POST['submit']) {
	// db() is a custom function written to abstract PHP queries
		//$query = "select * from User";
		$query = "SELECT U.Username AS Username, R.Username AS Resident_Name, 
									P.Username AS Prospective_Name, 
									M.Username AS Manager_Name, R.Apt_No AS Apt_No
				  FROM User AS U, Management AS M, 
				  	Prospective_Resident AS P LEFT JOIN Resident AS R ON P.Username = R.Username 
				  WHERE (U.Username = M.Username OR U.Username = P.Username) 
				  	AND U.Username = '$Username' AND U.Password = '$Password' LIMIT 1";
	    //echo $query;
		$result = db()->fetch($query);
		
		if (!$result) {
			$_SESSION['error'] = "Incorrect username or password. ";
			redirect('login');
			exit;
		} else if ($result['Resident_Name'] == $Username && !$result['Apt_No']) {
			$_SESSION['error'] = "Your application is pending. 
							You will be able to login once a manager allots you an apartment. ";
			redirect('login');
			exit;
		} else if ($result['Manager_Name'] != $Username && $result['Prospective_Name'] != $Username) {
			$_SESSION['error'] = "You never filled out your prospective resident form! ";
			redirect('application');
			exit;
		} else {
			// Login was successful
			// Not secure but for now we can just save username in a session variable.
			$_SESSION['username'] = $Username;
			if ($result['Manager_Name'] == $Username) {
				$_SESSION['is_manager'] = true;
				$_SESSION['notice'] = "You have successfully logged in as a manager. ";
			} else if ($result['Resident_Name'] == $Username) {
				$_SESSION['notice'] = "You have successfully logged in as a resident. ";
				$_SESSION['apt_no'] = $result['Apt_No'];
			} else {
				$_SESSION['error'] = "Your application was automatically rejected, so you cannot login. ";
				redirect('login');
				exit;
			}

			//Send the user to the home page
			redirect('home');
			exit;
		}
}
?>