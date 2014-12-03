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
	$today = date("Y-m-d");
	$effectiveDate = date('Y-m-d', strtotime("+2 months", strtotime($today)));
	if ($name == "" || $_POST['birthyear'] == "" || $_POST['birthmonth'] == "" 
			|| $_POST['birthday'] == "" || $category == "" || $pref_move_in == "" 
			|| $previous_address == "" || $pref_lease_term == "" || $gender == "" 
			|| $_POST['minrent'] == "" || $_POST['maxrent'] == "" || $_POST['income'] == "") {
		$_SESSION['error'] = "You must fill in all fields. ";
	} elseif($max_rent < $min_rent) {
		$_SESSION['error'] = "Your max rent cannot be lower than your minimum rent. ";
	} elseif($pref_move_in < $today || $pref_move_in > $effectiveDate){
		$_SESSION['error'] = "The prefered move in date must between today and two month from now! ";
	} elseif(!is_numeric($income)|| !is_numeric($max_rent) || !is_numeric($min_rent)) {
    	$_SESSION['error'] = "Your one of your input(income, maximum rent, minimum rent) was not numeric. ";
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
		}
	}
}
?>

