<?php
require '../config.php';
require 'ProfileRequest.class.php';/*
$profileRequest = new ProfileRequest();
$profileRequest->create(new Profile);//*/
$json = '
{
	"id" : "https://www.google.com/accounts/o8/id?id=AItOawnfWXPUnLKvaa5VRSoseoqAGKMB7tGASw0",
	"social_network" : "Google",
	"name" : "Bastien BLANCHARD",
	"first_name" : "Bastien",
	"last_name" : "BLANCHARD",
	"link" : "https://www.google.com/accounts/o8/id?id=AItOawnfWXPUnLKvaa5VRSoseoqAGKMB7tGASw0",
	"birthday" : "1988-08-13",
	"hometown" : "Saint Paul de Vence",
	"gender" : "M",
	"buddy_list" : null,
	"subscribtion_list" : null,
	"reputation" : null,
	"session" : null,
	"transaction_list" : null,
	"email" : null,
	"possword" : null,
	"profile_picture" : "http://www.google.com/ig/c/photos/private/AIbEiAIAAABECMCusuKX8-3ShAEiC3ZjYXJkX3Bob3RvKihmZTQxNTQyOWY4Mjk5ZTZlODljODc4ZDhlZTcwM2M0OGUwZDRjNjQxMAGC2lYmoQXC3tho_CBP6qrCdOU_yQ"
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