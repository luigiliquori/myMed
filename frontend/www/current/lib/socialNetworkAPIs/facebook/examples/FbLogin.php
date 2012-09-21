<?php		
		$app_id = "352079521548536";
		$app_secret = "c386710770c974bdb307e87d4a8fb4a6";
		$my_url = "http://mymed21.sophia.inria.fr/";
		
		//session_start();
		$code = $_REQUEST["code"];
		
		if(empty($code)) {
			$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
			$dialog_url = "http://www.facebook.com/dialog/oauth?client_id="
			. $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
			. $_SESSION['state'];
		
			echo("<script> top.location.href='" . $dialog_url . "'</script>");
		}
		
?>


