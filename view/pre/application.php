<?php 
if (!$_SESSION['username']) {
	$_SESSION['error'] = "You must be registered before you can fill out a prospective resident form. ";
	redirect('register');
	exit;
} else if (!$_SESSION['not_applied']) {
	$_SESSION['error'] = "You've already applied for a prospective resident application. ";
	redirect('index');
	exit;
}

if ($_POST['submit']) {
	$name = $_POST['name'];
	$birthday = $_POST['birthyear'] . '-' . $_POST['birthmonth']  . '-' . $_POST['birthday'];

	$category = $_POST['category'];
	$pref_move_in = $_POST['movein'];
	$previous_address = $_POST['prev_residence'];
	$min_rent = $_POST['minrent'];							
	$max_rent = $_POST['maxrent']; 
	$income = intval($_POST['income']); 
	$pref_lease_term = intval($_POST['lease']); 
	$gender = $_POST['gender'];
	$username = $_SESSION['username'];

	if ($name == "" || $_POST['birthyear'] == "" || $_POST['birthmonth'] == "" 
			|| $_POST['birthday'] == "" || $category == "" || $pref_move_in == "" 
			|| $previous_address == "" || $pref_lease_term == "" || $gender == "" 
			|| $_POST['minrent'] == "" || $_POST['maxrent'] == "" || $_POST['income'] == "") {
		$_SESSION['error'] = "You must fill in all fields. ";
	} elseif($max_rent < $min_rent) {
		$_SESSION['error'] = "Your max rent cannot be lower than your minimum rent. ";
	} else {
		$query = "INSERT INTO  Prospective_Resident (Date_of_Birth, Name, Req_Cat, Pref_Move, 
									Prev_Add, Min_Rent, Max_Rent, Monthly_Income, Pref_Lease_Term, Gender, Username) 
								VALUES ('$birthday', '$name', '$category', '$pref_move_in', '$previous_address', $min_rent, 
									$max_rent, $income, $pref_lease_term, '$gender', '$username')";
		$res = db()->query($query);
		if (!$res) {
			$_SESSION['error'] = "You've already submitted a prospective resident application form! " . db()->error();
			redirect('home');
			exit;
		} else {
			$_SESSION['notice'] = "Your application has been sent! You will be able to login once an apartment is alloted to you. ";

			$_SESSION['not_applied'] = false;

			redirect('index');
			exit;
		}
	}
}
?>