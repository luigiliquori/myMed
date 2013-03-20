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
<? include_once('utils.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

<head> 

	<title><?= empty($TITLE) ? APPLICATION_NAME : $TITLE ?></title> 
			
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	
	<!--  Extra icons for jquery -->
	<link rel="stylesheet" href="/lib/jquery/jqm-icon-pack-2.1.2-fa.css" />
	
	<!-- JQUERY  -->
	<link rel="stylesheet" href="/lib/jquery/jquery.mobile-1.2.0.css" />
	<script src="/lib/jquery/jquery-1.8.2.min.js"></script>
	<script src="/lib/jquery/jquery.mobile-1.2.0.js"></script>
	
	<!-- DASP css -->
	<link href="/system/css/common.css" rel="stylesheet" />
	
	<!-- DASP js -->
	<script src="/system/javascript/utils.js"></script>
	
	<!-- APP js -->
	<script src="javascript/app.js"></script>

	<!-- APP css -->
	<link href="css/myEurope.min.css" rel="stylesheet" />
	<link href="css/style.css" rel="stylesheet" />
	
	<!-- Cleeditor -->
	<link rel="stylesheet" type="text/css" href="jquery/CLEeditor/jquery.cleditor.css" />
    <script type="text/javascript" src="jquery/CLEeditor/jquery.cleditor.min.js"></script>
    <script type="text/javascript" src="jquery/CLEeditor/startCLE.js"> </script>
    
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
			
	</head>
		
<body>


