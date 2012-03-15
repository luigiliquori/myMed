<?php 
	define('APPLICATION_NAME', "myApplicationName");
	
	// LOAD THE UI FRAMEWORK
	echo '<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />';
	echo '<link rel="stylesheet" href="lib/jquery/jquery.mobile.actionsheet.css" />';
	echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>';
	echo '<script type="text/javascript" src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js"></script>';
	echo '<script type="text/javascript" src="lib/jquery/jquery.mobile.actionsheet.js"></script>';
	echo '<script src="lib/jquery/datebox/jquery.mobile.datebox.min.js"></script>';
	echo '<link href="lib/jquery/datebox/jquery.mobile.datebox.min.css" rel="stylesheet" />';
	
	// IMPORT THE MAIN VIEW
	require_once dirname(__FILE__).'/MyApplication.class.php';
	
	$app = new MyApplication("myApplicationName", "myApplicationName");
	$app->printTemplate();
?>

