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
    Prospective_Resident AS PR 
WHERE
    R.Username = PR.Username
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
    Prospective_Resident AS PR
WHERE
    R.Username = PR.Username 
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
    Prospective_Resident AS PR 
WHERE
    R.Username = PR.Username
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
	$query = "SELECT P.Pref_Move > DATE(NOW()) AS not_moved, P.Pref_Move 
							FROM Prospective_Resident AS P 
							WHERE P.Username = '$Username'";
	$res = db()->fetch($query);
	if ($res['not_moved']) {
		$_SESSION['notice'] = "You aren't moving in until $res[Pref_Move], so you don't owe any rent yet. ";
	}
	$check = "SELECT Card_No FROM Payment WHERE Month = $Month 
					AND Year = $Year AND Apt_No = $_SESSION[apt_no]";
	if (db()->numOfRows($check) > 0) {
		$_SESSION['notice'] = "You've already paid rent for this month, but you can still pay for future months. ";
	}
	
	$query = "SELECT 
    A.Rent AS Rent
    FROM
	    Apartment AS A,
	    Resident AS R,
	    Prospective_Resident AS PR 
		WHERE
	    R.Username = PR.Username
        AND A.Apt_No = R.Apt_No
        AND R.Username = '$Username'";
   $res = db()->fetch($query);
   $Rent = $res['Rent'];

   $query = "SELECT 
   		Pref_Move, 
    	MONTH(Pref_Move) AS Month, 
			YEAR(Pref_Move) AS Year, 
			DAY(Pref_Move) AS Day
    FROM
	    Prospective_Resident 
		WHERE
	    Username = '$Username'";

   if ($Pref_Move = db()->fetch($query)) {
   	if ($Pref_Move['Month'] != $Month || $Pref_Move['Year'] != $Year 
   		|| $Pref_Move['Day'] <= 3) {
   		$date = explode('-', $scheduleDate); // 0 => Year, 1 => Month, 2 => Day
   		$Day = intval($date[2]);
   		if ($Day > 3) {
   			$Rent += ($Day - 3) * 50;
   		}
   	} else if ($Pref_Move['Day'] > 7) {
   		$Rent = ((30 - $Pref_Move['Day']) / 30) * $Rent;
   	}
   }
} 
$Rent = round($Rent, 2);
if ($_POST['submit']) {
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
	} else if (round($Amount, 2) != $Rent) {
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
	} else {
		$_SESSION['error'] = "You've already paid rent for this month. ";
		redirect('rent');
		exit; 
	}
}
?>