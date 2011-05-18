<?php
require_once dirname(__FILE__).'/../config.php';
require_once dirname(__FILE__).'/ProfileRequest.class.php';
require_once dirname(__FILE__).'/LoginRequest.class.php';//*
$request = new LoginRequest();
echo $request->read("https://www.google.com/accounts/o8/id?id=AItOawnfWXPUnLKvaa5VRSoseoqAGKMB7tGASw0", "Google");//*/
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
var_dump(json_decode($json));
switch(json_last_error())
{
	case JSON_ERROR_NONE			:echo 'JSON_ERROR_NONE';break;
	case JSON_ERROR_DEPTH			:echo 'JSON_ERROR_DEPTH';break;
	case JSON_ERROR_CTRL_CHAR		:echo 'JSON_ERROR_CTRL_CHAR';break;
	case JSON_ERROR_STATE_MISMATCH	:echo 'JSON_ERROR_STATE_MISMATCH';break;
	case JSON_ERROR_SYNTAX			:echo 'JSON_ERROR_SYNTAX';break;
	case JSON_ERROR_UTF8			:echo 'JSON_ERROR_UTF8';break;
}
var_dump(Profile::__set_state(json_decode($json, true)));
switch(json_last_error())
{
	case JSON_ERROR_NONE			:echo 'JSON_ERROR_NONE';break;
	case JSON_ERROR_DEPTH			:echo 'JSON_ERROR_DEPTH';break;
	case JSON_ERROR_CTRL_CHAR		:echo 'JSON_ERROR_CTRL_CHAR';break;
	case JSON_ERROR_STATE_MISMATCH	:echo 'JSON_ERROR_STATE_MISMATCH';break;
	case JSON_ERROR_SYNTAX			:echo 'JSON_ERROR_SYNTAX';break;
	case JSON_ERROR_UTF8			:echo 'JSON_ERROR_UTF8';break;
}
?>