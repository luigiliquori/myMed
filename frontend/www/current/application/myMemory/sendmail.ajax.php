<?php
/**
 * Script called in ajax to send the notification by e-mail
 */

include("models/EmailNotification.class.php");
if($_POST)
{
	$debug = array(
	"mail"=>$_POST['email'],
	"name"=>$_POST['username'],
	"street"=>$_POST['current_street'],
	"latlng"=>array(	"lat"=>$_POST['current_lat'],
						"lng"=>$_POST['current_lng'])
	);
	
	$mail = new EmailNotification($_POST['email'],
									$_POST['username'],
									$_POST['current_street'],
									array(	"lat"=>$_POST['current_lat'],
											"lng"=>$_POST['current_lng']));
	$mail->send();
	echo json_encode(array("success"=>"message bien envoyé!"));
}else { }


?>