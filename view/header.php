<?php 
global $page_title, $headerStuff, $includes; 
?><!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title><?php echo SITE_NAME . ' || ' . $page_title; ?></title>
  <meta name="description" content="Apartment Management">
  <meta name="author" content="Sen Lin, Daniel Carnaúba, Sherman Matthews, Tiffany Zhang">

  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="<?php echo  SITE_PATH; ?>static/js/jquery-ui-1.11.2/jquery-ui.min.js"></script>
  <script src="<?php echo  SITE_PATH; ?>static/js/global.js"></script>
  <?php 
  if (is_array($includes['js'])) {
    foreach ($includes['js'] as $js) {
      echo '<script src="' . SITE_PATH . $js . '"></script>';
    }
  }
  ?>

  <link href='http://fonts.googleapis.com/css?family=PT+Serif' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="<?php echo  SITE_PATH; ?>static/js/jquery-ui-1.11.2/jquery-ui.min.css">
  <link rel="stylesheet" href="<?php echo  SITE_PATH; ?>static/css/style.css">

  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>
<body>
	<div id="container">
		<h1>
			<a href="<?php echo  SITE_PATH; ?>">Sen, Daniel, Sherman, and Tiff's Apartment Management App</a>
		</h1>

<?php 
  if ($_SESSION['error']) {
    echo '<div class="error">' . $_SESSION['error'] . '</div>';
    $_SESSION['error'] = false;
  }

  if ($_SESSION['notice']) {
    echo '<div class="notice">' . $_SESSION['notice'] . '</div>';
    $_SESSION['notice'] = false;
  }
?>