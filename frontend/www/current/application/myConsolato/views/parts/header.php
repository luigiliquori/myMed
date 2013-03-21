<?php
/*
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
 */
?>
<? include_once('view-utils.php'); ?>
<!doctype html>
<html lang="fr" <? if (defined("DEMO")) print 'manifest="cache.manifest"' ?>> 

<head> 

	<title><?= empty($TITLE) ? "MyConsolato" : $TITLE ?></title> 
			
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
	
	<!-- JQUERY  -->
	<link rel="stylesheet" href="../../lib/jquery/jquery.mobile-1.1.0.min.css" />
	<script src="../../lib/jquery/jquery-1.7.2.min.js"></script>
	<script src="../../lib/jquery/jquery.mobile-1.1.0.min.js"></script>
	
	<!--  Extra icons for jquery -->
	<link rel="stylesheet" href="../../lib/jquery/extra-icons/jqm-icon-pack-2.0-original.css" />
	
	<!-- DateBox -->
	<script src="../../lib/jquery/datebox/jqm-datebox-1.1.0.core.min.js"></script>
	<script src="../../lib/jquery/datebox/jqm-datebox-1.1.0.comp.datebox.min.js"></script>
	<script src="../../lib/jquery/datebox/jquery.mobile.datebox.i18n.fr.utf8.js"></script>
	<link href="../../lib/jquery/datebox/jqm-datebox-1.1.0.min.css" rel="stylesheet" />
	
	<!-- Keyfilter  -->
	<script src="../../lib/jquery/jquery.keyfilter.js" ></script>
	
	<!--  SimpleDialog -->
	<script src="../../lib/jquery/simpledialog/jquery.mobile.simpledialog2.min.js"></script>
	<link href="../../lib/jquery/simpledialog/jquery.mobile.simpledialog.min.css" rel="stylesheet" />
	
	<!-- Localization -->
	<script type="text/javascript">
		msg={};
		msg.FORM_ERROR = "<?= _("Il y a des erreurs dans le formulaire, merci de les corriger.") ?>";
	</script>
	
	<!-- CLE editor -->
	<link rel="stylesheet" type="text/css" href="../../lib/jquery/CLEeditor/jquery.cleditor.css" />
	<script type="text/javascript" src="../../lib/jquery/CLEeditor/jquery.cleditor.min.js"></script>
	
	<!-- APP JS -->
	<script src="javascript/app.js"></script>
	
	<!-- MYMED css -->
	<!-- <link href="../myMed/css/style.css" rel="stylesheet" /> -->
	
	<!-- APP css -->
	<link href="css/style.css" rel="stylesheet" />
	
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

<? // ================== Switch to active tab on load ==========================================?>
<? if (!empty($TAB)) :?>
	<script type="text/javascript">
		$(document).ready(function() {
			$.mobile.changePage("#<?= $TAB ?>", {transition:"none"})
		});
	</script>
<? endif ?>



