<?php

require_once "oid-common.php";

// Starts session (needed for YADIS)
session_start();

// Create file storage area for OpenID data
$store = new Auth_OpenID_FileStore(getStorePath());

// Create OpenID consumer
$consumer = new Auth_OpenID_Consumer($store);

// Create an authentication request to the OpenID provider
$response = $consumer->complete(getReturnTo());


if ($response->status == Auth_OpenID_SUCCESS) {
	// Get registration informations
	$ax = new Auth_OpenID_AX_FetchResponse();
	$obj = $ax->fromSuccessResponse($response);
	
	//should fetch Sreg also here
	
	require_once ROOT.'lib/dasp/beans/MUserBean.class.php';
	
	$_SESSION['userFromExternalAuth'] = MUserBean::constructFromOpenId($obj->data);
	$_SESSION['accessToken'] = sha1($_SESSION['user']->email . time());
	
	
	//other useless stuff for try ------------
	$openid = $response->getDisplayIdentifier();
	$esc_identity = escape($openid);
	
	$success = sprintf('You have successfully verified ' .
			'<a href="%s">%s</a> as your identity.',
			$esc_identity, $esc_identity);
	
	if ($response->endpoint->canonicalID) {
		$escaped_canonicalID = escape($response->endpoint->canonicalID);
		$success .= '  (XRI CanonicalID: '.$escaped_canonicalID.') ';
	}
	
	$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
	
	$sreg = $sreg_resp->contents();
	
	if (@$sreg['email']) {
		$success .= "  You also returned '".escape($sreg['email']).
		"' as your email.";
	}
	
	if (@$sreg['nickname']) {
		$success .= "  Your nickname is '".escape($sreg['nickname']).
		"'.";
	}
	
	if (@$sreg['fullname']) {
		$success .= "  Your fullname is '".escape($sreg['fullname']).
		"'.";
	}
	
	$pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);
	
	if ($pape_resp) {
		if ($pape_resp->auth_policies) {
			$success .= "<p>The following PAPE policies affected the authentication:</p><ul>";
	
			foreach ($pape_resp->auth_policies as $uri) {
				$escaped_uri = escape($uri);
				$success .= "<li><tt>$escaped_uri</tt></li>";
			}
	
			$success .= "</ul>";
		} else {
			$success .= "<p>No PAPE policies affected the authentication.</p>";
		}
	
		if ($pape_resp->auth_age) {
			$age = escape($pape_resp->auth_age);
			$success .= "<p>The authentication age returned by the " .
					"server is: <tt>".$age."</tt></p>";
		}
	
		if ($pape_resp->nist_auth_level) {
			$auth_level = escape($pape_resp->nist_auth_level);
			$success .= "<p>The NIST auth level returned by the " .
					"server is: <tt>".$auth_level."</tt></p>";
		}
	
	} else {
		$success .= "<p>No PAPE response was sent by the provider.</p>";
	}
	// end of useless
	$_SESSION['user3'] = $success;
	
	
	header('Location: '.getTrustRoot().$_SESSION['appliName'].'?action=login');


} else {
	print('failed ;(');
}
function escape($thing) {
	return htmlentities($thing);
}

?>