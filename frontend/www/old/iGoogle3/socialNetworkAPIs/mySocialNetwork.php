<?php
/******************************************************************
 * VISITEUR (Default values)
 ******************************************************************/

/******************************************************************
 * MYMED
 ******************************************************************/

// Inscription
$time = date('l jS \of F Y h:i:s A');
if($_POST["inscription"])
{
	$_SESSION['user'] = array(
			'id'				=> $_POST['email'],
			'name'				=> $_POST['prenom'].' '.$_POST['nom'],
			'gender'			=> $_POST['gender'],
			'locale'			=> 'Francais',
			'updated_time'		=> $time,
			'profile'			=> '?profile='.$_POST['prenom'].$_POST['nom'],
			'profile_picture'	=> 'http://graph.facebook.com//picture?type=large',
			'social_network'	=> 'myMed',
			'email'				=> $_POST['email'],
			'password'			=> $_POST['password']);
}
/******************************************************************
 * FACEBOOK
 ******************************************************************/


/******************************************************************
 * TWITTER NEED PHP5-CURL PACKAGE
 ******************************************************************/
/******************************************************************
 * GOOGLE
 ******************************************************************/


// Store this information into the myMed network
file_get_contents(BACKEND_URL."RequestHandler?act=4&key1=" . $key . "&value1=" . $value);

$user = $_SESSION['user'];
$friends = $_SESSION['friends'];

// INSCRIPTION Store this information into the myMed network and mark session as "logged"
if($user->name && !$_SESSION['logged'])
{
	$encoded = json_encode($user);
	$result_getcontents = file_get_contents(trim(BACKEND_URL."RequestHandler?act=10&user=" . urlencode($encoded)));
	$_SESSION['logged'] = true;
}
?>