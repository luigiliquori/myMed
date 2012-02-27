<?php

if(isset($_GET['application']) && $_GET['application'] != '0') {
	require_once 'system/templates/application/' . $_GET['application'] . '/views/login/LoginDesktop.class.php';
	$login = new LoginDesktop();
} else {
	require_once dirname(__FILE__).'/Login.class.php';
	$login = new Login();
}
$login->printTemplate();

include('inscription.php');
include('socialNetwork.php');

?>