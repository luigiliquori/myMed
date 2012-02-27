<?php

if(isset($_SESSION['application']) && $_SESSION['application'] != '0') {
	require_once dirname(__FILE__).'system/templates/application/' . $_SESSION['application'] . '/' . $_SESSION['application'] . 'LoginMobile.class.php';
	$login = new Login();
} else {
	require_once dirname(__FILE__).'/Login.class.php';
	$login = new Login();
}

$login->printTemplate();

?>