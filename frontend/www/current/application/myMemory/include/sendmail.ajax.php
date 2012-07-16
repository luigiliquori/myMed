<?php
/**
 * Script called in ajax to send the notification by e-mail
 */
if($_POST)
{
	$mail = new EmailNotification($_POST['email'],
									$_SESSION['user']->name,
									$_POST['current_street'],
									array(	"lat"=>$_POST['current_lat'],
											"lng"=>$_POST['current_lng']));
	$mail->send();
	//echo json_encode(array("success"=>"message bien envoyé!"));
}else { }


?>