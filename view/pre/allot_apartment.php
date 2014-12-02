<?php 
if (!$_POST['username']) {
	$_SESSION['error'] = 'No user selected! ';
	redirect('application_review');
	exit;
}
?>