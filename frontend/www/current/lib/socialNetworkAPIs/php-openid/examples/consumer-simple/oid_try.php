<?php

require_once "oid-common.php";

// Starts session (needed for YADIS)
session_start();

define("APPLICATION_NAME", $_GET['applicationname']);
// 'applicationname' is defined in the LoginView.php href for OpenID;
// used to redirect to the application main (myEurope for example) from the OpenID login (not to mymed)

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


// add  SREG, not needed for google
$sreg_request = Auth_OpenID_SRegRequest::build(
		// Required
		array('nickname', 'email'),
		// Optional
		array('fullname'));
if ($sreg_request) {
	$auth->addExtension($sreg_request);
}
$policy_uris = null;
if (isset($_GET['policies'])) {
	$policy_uris = $_GET['policies'];
}

$pape_request = new Auth_OpenID_PAPE_Request($policy_uris);
if ($pape_request) {
	$auth->addExtension($pape_request);
}
// ---- end of the unneeded stuff -- just testing



// Redirect to OpenID provider for authentication
$url = $auth->redirectURL(getTrustRoot(), getReturnTo());
header('Location: ' . $url);


?>