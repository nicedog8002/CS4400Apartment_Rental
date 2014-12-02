<?php 
// The keys of the $_POST object are based on the "name" attribute
// of the HTML forms which submit to this page
$Month = intval($_POST['rentmonth']);
$Year = intval($_POST['rentyear']);
$Card_No = $_POST['card'];
$scheduleDate = $_POST['date'];
$Amount = intval($_POST['amount']);
$Apt_No = intval($_POST['apt_no']);

if ($_POST['submit']) {
	if (!$MONTH || $Year){
		$_SESSION['error'] = "You must pick the date.";
		redirect('rent');
		exit;
	} else if (!$Card_No){
		$_SESSION['error'] = "You must enter you card number. ";
		redirect('rent');
		exit;
	} else if (!$scheduleDate) {
		$_SESSION['error'] = "You must schedule your payment date. ";
		redirect('rent');
		exit;
	} else if ($Amount) {
		$_SESSION['error'] = "You must enter the amount. ";
		redirect('rent');
		exit;
	} else if ($Apt_No) {
		$_SESSION['error'] = "You must enter your apartment number. ";
		redirect('rent');
		exit;
	}
	// Create a Date
	$query = "INSERT INTO Date 
		SELECT * FROM (SELECT $Month, $Year) AS newEntry
  		WHERE NOT EXISTS (SELECT * FROM Date
        WHERE Date.Month = $Month AND Date.Year = $Year)";
	db()->query($query);

	// db() is a custom function written to abstract PHP queries
	$query = "INSERT INTO Payment (Card_No, Month, Year, Apt_No, Date_of_Payment, Amount)
						SELECT * FROM (SELECT $Card_No, MONTH('$scheduleDate'), YEAR('$scheduleDate'), 
							$Apt_No, '$scheduleDate', $Amount) AS newEntry
						WHERE NOT EXISTS (SELECT * FROM Payment AS P WHERE P.Month = MONTH('$scheduleDate') 
							AND P.Year = YEAR('$scheduleDate') AND P.Card_No = $Card_No)
							AND MONTH('$scheduleDate') > 0 AND MONTH('$scheduleDate') <= 12 
							AND YEAR('$scheduleDate') > 2000 AND YEAR('$scheduleDate') < 2099";
	if (db()->query($query)) {
		$_SESSION['notice'] = "You've successfuly submitted your rent payment! ";
		redirect('home');
		exit; 
	} else {
		$_SESSION['error'] = "You've already paid rent for this month. ";
		redirect('rent');
		exit; 
	}
}
?>