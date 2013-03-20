<!--
 * Copyright 2013 INRIA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

<head> 

	<title><?= empty($TITLE) ? APPLICATION_NAME : $TITLE ?></title> 
			
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
	
	<!-- JQUERY  -->
	<link rel="stylesheet" href="/lib/jquery/jquery.mobile-1.2.0.css" />
	<script src="/lib/jquery/jquery-1.8.2.min.js"></script>
	<script src="/lib/jquery/jquery.mobile-1.2.0.js"></script>
	
	<!-- jQuery icons pack -->
	<link href="<?= MYMED_URL_ROOT ?>/lib/jquery/jQuery-Mobile-Icon-Pack/original/jqm-icon-pack-2.0-original.css" rel="stylesheet" />
	<link href="<?= MYMED_URL_ROOT ?>/lib/jquery/jQuery-Mobile-Icon-Pack/font-awesome/jqm-icon-pack-2.1.2-fa.css" rel="stylesheet" />
	
	<!--  SimpleDialog -->
	<script src="<?= MYMED_URL_ROOT ?>/lib/jquery/simpledialog/jquery.mobile.simpledialog2.min.js"></script>
	<link href="<?= MYMED_URL_ROOT ?>/lib/jquery/simpledialog/jquery.mobile.simpledialog.min.css" rel="stylesheet" />
	
	<!-- APP JS -->
	<script src="<?= APP_ROOT ?>/javascript/app.js"></script>
	
	<!-- APP css -->
	<link href="<?= APP_ROOT ?>/css/style.css" rel="stylesheet" />
	
	<!-- DASP JS -->
	<script src="<?= MYMED_URL_ROOT ?>/lib/dasp/javascript/dasp.js"></script>
	
	<!-- GOOGLE MAP -->
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true&libraries=places"></script>
	<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox_packed.js"></script>
	
	<!-- Google Analytics -->
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-31172274-1']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
	
	<!-- Share this -->
	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "f0ca88be-be8a-427a-983a-f670821d7ad2"}); </script>
			
	</head>
		
<body>

<input type='hidden' id='userID' value='<?= $_SESSION['user']->id ?>' />
<input type='hidden' id='applicationName' value='<?= APPLICATION_NAME ?>' />
<input type='hidden' id='accessToken' value='<?= $_SESSION['accessToken'] ?>' />


