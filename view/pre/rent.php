<?php 
global $Rent;
// The keys of the $_POST object are based on the "name" attribute
// of the HTML forms which submit to this page
$Month = $_POST['rentmonth'];
$Year = $_POST['rentyear'];
$scheduleDate = $_POST['date'];

$Month = $Month ? $Month : date('n');
$Year = $Year ? $Year : date('Y');
$scheduleDate = $scheduleDate ? $scheduleDate :  date('Y-m-d');

$Card_No = $_POST['card'];
$Amount = intval($_POST['amount']);
$Apt_No = intval($_POST['apt_no']);
$Username = $_SESSION['username'];

$query0 = "DROP TABLE if exists TEMP";
$query1 = "CREATE TABLE TEMP(calculated_rent int)";
$query2 = "
      INSERT INTO TEMP
SELECT 
    A.Rent AS Calculated_Rent
FROM
    Apartment AS A,
    Resident AS R,
    Prospective_Resident AS PR,
    Payment_Information AS PI
WHERE
    R.Username = PR.Username
        AND R.Username = PI.Username
        AND A.Apt_No = R.Apt_No
        AND R.Username = '$Username'
        AND DATE(NOW()) >= Pref_Move
        AND DATE(NOW()) <= '$scheduleDate'
    AND (DAY(Pref_Move)) <= 7
        AND ((MONTH('$scheduleDate') = MONTH(Pref_Move)
        AND DAY('$scheduleDate') <= 7)
        OR (MONTH(Pref_Move) < MONTH('$scheduleDate')
        AND DAY('$scheduleDate') <= 3)
        OR (MONTH('$scheduleDate') < $Month
        AND YEAR('$scheduleDate') <= $Year)
        
        OR (MONTH(pref_move) = $Month 
        AND YEAR(pref_move) = $Year 
        AND DAY(pref_move) <= 7 AND DAY(pref_move) > 3))
  
        AND A.Apt_NO NOT IN (SELECT 
            Apt_NO
        FROM
            Payment
    WHERE month = $Month)
UNION SELECT 
    A.Rent + (DAY('$scheduleDate') - 3) * 50 AS Calculated_Rent
FROM
    Apartment AS A,
    Resident AS R,
    Prospective_Resident AS PR,
    Payment_Information AS PI
WHERE
    R.Username = PR.Username
        AND R.Username = PI.Username
        AND A.Apt_No = R.Apt_No
        AND R.Username = '$Username'
        AND DATE(NOW()) >= Pref_Move
        AND MONTH(NOW()) >= MONTH(Pref_Move)
        AND NOT (DAY(Pref_Move) > 7 OR MONTH(Pref_Move) < MONTH('$scheduleDate') OR YEAR(Pref_Move) < YEAR('$scheduleDate'))
        AND DATE(NOW()) <= '$scheduleDate'
        AND DAY('$scheduleDate') > 3
        AND (MONTH('$scheduleDate') >= $Month
        AND YEAR('$scheduleDate') >= $Year)
        AND A.Apt_NO NOT IN (SELECT 
            Apt_NO
        FROM
            Payment
    WHERE month = $Month)
UNION SELECT 
    ((30 - DAY(Pref_Move)) / 30) * A.Rent AS Calculated_Rent
FROM
    Apartment AS A,
    Resident AS R,
    Prospective_Resident AS PR,
    Payment_Information AS PI
WHERE
    R.Username = PR.Username
        AND R.Username = PI.Username
        AND A.Apt_No = R.Apt_No
        AND R.Username = '$Username'
        AND DATE(NOW()) >= Pref_Move
        AND DATE(NOW()) <= '$scheduleDate'
        AND MONTH('$scheduleDate') = MONTH(Pref_Move)
        AND YEAR('$scheduleDate') = YEAR(Pref_Move)
        AND DAY(Pref_Move) > 7
        AND A.Apt_NO NOT IN (SELECT 
            Apt_NO
        FROM
            Payment
    WHERE month = $Month)";
$query3 = "SELECT MAX(calculated_rent) AS rent FROM TEMP";

db()->query($query0);
db()->query($query1);
db()->query($query2);
$res = db()->fetch($query3);
$Rent = intval($res['rent']);

if (!$Rent) {
	$_SESSION['notice'] = "You've already paid your rent for this month, 
									but you're free to pay for future months. ";
} else if ($_POST['submit']) {
	if (!$Month || !$Year){
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
	} else if (!$Amount) {
		$_SESSION['error'] = "You must enter the amount. ";
		redirect('rent');
		exit;
	} else if (!$Apt_No) {
		$_SESSION['error'] = "You must enter your apartment number. ";
		redirect('rent');
		exit;
	} else if ($Amount != $Rent) {
		$_SESSION['error'] = "You must pay the exact rent you owe. ";
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
						VALUES ('$Card_No', $Month, $Year, 
							$Apt_No, '$scheduleDate', $Amount)";
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