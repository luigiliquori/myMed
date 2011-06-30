<?php
global $users;
$users = Array();
$users['test'] = new Profile;
$users['test']->socialNetworkID		= 1;
$users['test']->socialNetworkName	= 'myMed';
$users['test']->name			= 'Firstname LASTNAME';
$users['test']->firstName		= 'Firstname';
$users['test']->lastName		= 'LASTNAME';
//$users['test']->link			= 'http://mymed21.sophia.inria.fr/';
$users['test']->birthday		= '2011-05-26';
$users['test']->hometown		= 'Biot';
$users['test']->gender			= 'M';
$users['test']->email			= 'firstname.lastname@mymed.fr';
$users['test']->password		= 'test';
//$users['test']->profilePicture	= 'http://mymed21.sophia.inria.fr/';

function getUserByLogin(/*string*/ $login)
{
	global $users;
	return @$users[$login];
}
?>
