<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        
<!-- LOADING FACEBOOK -->
<?php
	define('FACEBOOK_APP_ID', '154730914571286');
	define('FACEBOOK_SECRET', '06b728cd7b6527c7cc2af70b3581bbf3');

	function get_facebook_cookie($app_id, $application_secret) {
	  $args = array();
	  parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
	  ksort($args);
	  $payload = '';
	  foreach ($args as $key => $value) {
	    if ($key != 'sig') {
	      $payload .= $key . '=' . $value;
   	 	}
  	  }
  	  if (md5($payload . $application_secret) != $args['sig']) {
    	return null;
  	  }
  	  return $args;
	}

	$cookie = get_facebook_cookie(FACEBOOK_APP_ID, FACEBOOK_SECRET);

	if($_GET["try"] == 1){
		 setcookie("try", 1);
	}
?>

<!-- MAIN -->
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://www.facebook.com/2008/fbml">
      
  <head>
  	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	
  	<!-- SHARE THIS -->
    <script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
    <script type="text/javascript">stLight.options({publisher:'8bbeee4c-99fe-4920-85a2-4417288a1bde'});</script>
  
  	<!-- MY APPs -->
  	<script type="text/javascript" src="javascript/cookie.js"></script>
	<script type="text/javascript" src="javascript/drag.js"></script>
	<script type="text/javascript" src="javascript/display.js"></script>
	<script type="text/javascript" src="javascript/requestHTML.js"></script>
	<script type="text/javascript" src="javascript/jquery/dist/jquery.js"></script>
	
	<!-- FACEBOOK APIs -->
	<script src="http://connect.facebook.net/en_US/all.js"></script>
	
	
	<!-- TITLE -->
	<title>myMed</title>
	
	<!-- STYLESHEET -->
  	<link rel="stylesheet" href="css/style.css"> 
  </head>
      
  <body onclick="cleanView();">
    <div align="center">
	  	
	  	<!-- HEADER -->
		<?php include('header.php'); ?>
	  	
	  	<!-- CONTENT -->
	  	<div id="content">
	  		<?php if ($user || $_COOKIE["try"] == "1" || $_GET["try"] == 1) { ?>
	  		
		  		<!-- USERINFO -->
				<?php include('user.php'); ?>
				
				<!-- FIRENDS -->
				<?php include('friends.php'); ?>
				
				<!-- DESKTOP -->
				<?php include('desktop.php'); ?>
			
				<!-- DOCK -->
				<?php include('dock.php'); ?>

				<!-- MYMEDSTORE -->
				<?php include('myMedStore.php'); ?>
					
				<!-- MYTRANSPORT -->
				<?php include('myTransport.php'); ?>
				
				<!-- WARNING -->
				<?php include('warning.php'); ?>
				
				<!-- GOOGLE MAP -->
				
	    	<?php } else { ?>
				
				<!-- ACCUEIL -->
				<?php include('accueil.php'); ?>
				
			<?php } ?>
	    	
    	</div>
    	
    	<!-- FOOTER -->
		<?php include('footer.php'); ?>
    	
    </div>
   	
   	<!-- Init facebook APIs -->
   	<div id="fb-root"></div>
	<script>
	      FB.init({appId: '<?= FACEBOOK_APP_ID ?>', status: true,
	               cookie: true, xfbml: true});
	      FB.Event.subscribe('auth.login', function(response) {
	        window.location.reload();
	      });
	</script>
	

  </body>
</html>
