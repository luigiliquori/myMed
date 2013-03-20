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
<? include_once('header-bar-light.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

<head> 

	<title><?= empty($TITLE) ? APPLICATION_NAME : $TITLE ?></title> 
			
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
	
	<!-- load css before scripts to stop sort of flash effect  -->
	
	<!--  Extra icons for jquery -->
	<link rel="stylesheet" href="../../lib/jquery/jqm-icon-pack-2.1.2-fa.css" />
	
	<!-- JQUERY  -->
	<link rel="stylesheet" href="../../lib/jquery/jquery.mobile-1.2.0-beta.1.css" />
	<!-- APP css -->
	<link href="<?= APP_ROOT ?>/css/style.css" rel="stylesheet" />
	<!-- MYMED css -->
	<link href="<?= MYMED_URL_ROOT ?>/system/css/common.css" rel="stylesheet" />	
	
	<script src="../../lib/jquery/jquery-1.8.1.min.js"></script>
	<script src="../../lib/jquery/jquery.mobile-1.2.0-beta.1.js"></script>
	<!-- APP JS -->
	<script src="<?= APP_ROOT ?>/javascript/app.js"></script>
	
  	<!-- Graphs -->
  	<script src="<?= APP_ROOT ?>/javascript/statistics-graphics.js"></script>
	
	
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
	
	<script type="text/javascript" src="<?= APP_ROOT ?>/javascript/gadash-1.0.js"></script>
	
	
	</head>
		
<body>

<? // ================== Switch to active tab on load ==========================================?>
<? if (!empty($TAB)) :?>
	<script type="text/javascript">
		$('[data-role=page]:last').live("pageshow", function() {
			$.mobile.changePage("#<?= $TAB ?>", {transition:"none"});
		});
	</script>
<? endif ?>

