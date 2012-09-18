<?php

require_once "oid-common.php";

// Starts session (needed for YADIS)
session_start();

$oid_identifier = isset($_GET['openid_identifier'])?
					$_GET['openid_identifier']:
					'https://www.google.com/accounts/o8/id';

// Create file storage area for OpenID data
$store = new Auth_OpenID_FileStore(getStorePath());

// Create OpenID consumer
$consumer = new Auth_OpenID_Consumer($store);

// Create an authentication request to the OpenID provider
$auth = $consumer->begin($oid_identifier);

// Create attribute request object
// See http://code.google.com/apis/accounts/docs/OpenID.html#Parameters for parameters
// Usage: make($type_uri, $count=1, $required=false, $alias=null)
$attribute[] = Auth_OpenID_AX_AttrInfo::make('http://axschema.org/contact/email',2,1, 'email');
$attribute[] = Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/first',1,1, 'firstname');
$attribute[] = Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/last',1,1, 'lastname');
$attribute[] = Auth_OpenID_AX_AttrInfo::make('http://axschema.org/pref/language',1,1, 'language');

// Create AX fetch request
$ax = new Auth_OpenID_AX_FetchRequest;

// Add attributes to AX fetch request
foreach($attribute as $attr){
	$ax->add($attr);
}

// Add AX fetch request to authentication request
$auth->addExtension($ax);

// Redirect to OpenID provider for authentication
$url = $auth->redirectURL(getTrustRoot(), getReturnTo());
header('Location: ' . $url);


?>