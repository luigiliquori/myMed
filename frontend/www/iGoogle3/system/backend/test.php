<?php
require_once dirname(__FILE__).'/../config.php';
require_once dirname(__FILE__).'/ProfileRequest.class.php';
require_once dirname(__FILE__).'/SessionRequest.class.php';//*
$request = new ProfileRequest();
//echo $request->read("https://www.google.com/accounts/o8/id?id=AItOawnfWXPUnLKvaa5VRSoseoqAGKMB7tGASw0", "Google");//*/
$json = '
{
	"mymedID" : "Googlehttps://www.google.com/accounts/o8/id?id=AItOawnfWXPUnLKvaa5VRSoseoqAGKMB7tGASw0",
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
	"possword" : null,
	"profilePicture" : "http://www.google.com/ig/c/photos/private/AIbEiAIAAABECMCusuKX8-3ShAEiC3ZjYXJkX3Bob3RvKihmZTQxNTQyOWY4Mjk5ZTZlODljODc4ZDhlZTcwM2M0OGUwZDRjNjQxMAGC2lYmoQXC3tho_CBP6qrCdOU_yQ"
}';
$profile = new Profile($json);
var_dump($profile);
var_dump($request->create($profile));
var_dump($request->read($profile->mymedID));
var_dump($request->read('g'));
?>
