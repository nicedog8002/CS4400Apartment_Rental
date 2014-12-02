<?php 
// The keys of the $_POST object are based on the "name" attribute
// of the HTML forms which submit to this page
if ($requests = $_POST['requests']) {
	$count = 0;
	foreach ($requests as $req) {
		$count++;
		$vars = explode(',', $req);
		$Request_Date = $vars[0];
		$Apt_No = $vars[1];
		$Issue_Type = $vars[2];
		$query = "UPDATE Maintenance_Request 
				SET Issue_Status = 'resolved' 
				AND Date_Resolved = GETDATE() 
				WHERE Date_Of_Request = $Request_Date 
				AND Apt_No = $Apt_No 
				AND Issue_Type = $Issue_Type";
	}
	$_SESSION['notice'] = "Resolved $count issues. ";
}
?>