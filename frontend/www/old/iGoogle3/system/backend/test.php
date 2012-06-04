<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/ProfileRequest.class.php';
require_once __DIR__.'/SessionRequest.class.php';//*
$request = new ProfileRequest();
//echo 'delete http://mymed21.sophia.inria.fr/mymed/iGoogle3/openid/idpage/test<br>';
//$request->delete('myMedhttp://mymed21.sophia.inria.fr/mymed/iGoogle3/openid/idpage/test');
//echo $request->read("https://www.google.com/accounts/o8/id?id=AItOawnfWXPUnLKvaa5VRSoseoqAGKMB7tGASw0", "Google");//*/
$json = '
{
	"id" : "Googlehttps://www.google.com/accounts/o8/id?id=AItOawnfWXPUnLKvaa5VRSoseoqAGKMB7tGASw0",
	"socialNetworkID" : "https://www.google.com/accounts/o8/id?id=AItOawnfWXPUnLKvaa5VRSoseoqAGKMB7tGASw0",
	"socialNetworkName" : "Google",
	"name" : "Bastien BLANCHARD",
	"firstName" : "Bastien",
	"lastName" : "BLANCHARD",
	"link" : "https://www.google.com/accounts/o8/id?id=AItOawnfWXPUnLKvaa5VRSoseoqAGKMB7tGASw0",
	"birthday" : "1988-08-13",
	"hometown" : "Saint Paul de Vence",
	"gender" : "M",
	"buddyList" : null,
	"subscribtionList" : null,
	"reputation" : null,
	"session" : null,
	"transactionList" : null,
	"email" : null,
	"password" : null,
	"profilePicture" : "http://www.google.com/ig/c/photos/private/AIbEiAIAAABECMCusuKX8-3ShAEiC3ZjYXJkX3Bob3RvKihmZTQxNTQyOWY4Mjk5ZTZlODljODc4ZDhlZTcwM2M0OGUwZDRjNjQxMAGC2lYmoQXC3tho_CBP6qrCdOU_yQ"
}';
$profile = new Profile($json);
echo '$profile=<br>';
var_dump($profile);
echo '$request->create($profile)=<br>';
var_dump($request->create($profile));
echo '$request->read($profile->id)=<br>';
var_dump($request->read($profile->id));
echo '$request->read(\'visiteur\')=<br>';
var_dump($request->read('visiteur'));
$profile->id = 'wx';
echo '$request->read(\'wx\')=<br>';
try
{
	var_dump($request->read($profile->id));
}
catch(Exception $ex)
{
	var_dump($ex);
	echo $ex;
}
?>
