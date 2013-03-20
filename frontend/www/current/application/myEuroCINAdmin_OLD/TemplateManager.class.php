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
<?php

class TemplateManager {
	
	/**
	 * Print the http header
	 */
	public function getHeader(){ ?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">  

		<head> 
			<title>myEuroCIN | Réseaux Social Transfrontalier</title> 
			
			<meta http-equiv="content-type" content="text/html;charset=utf-8" />
			<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0" />
			
			<!-- JQUERY -->
			<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.css" />
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
			<script type="text/javascript">
			$(document).bind("mobileinit", function(){
				$.mobile.loadingMessageTextVisible = true;
			});
			</script>
			<script src="http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"></script>
			
			<!-- DateBox -->
			<script src="../../lib/jquery/datebox/jquery.mobile.datebox.min.js"></script>
			<link href="../../lib/jquery/datebox/jquery.mobile.datebox.min.css" rel="stylesheet" />
			
			<!-- CLEeditor -->
    		<link rel="stylesheet" type="text/css" href="../../lib/jquery/CLEeditor/jquery.cleditor.css" />
    		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    		<script type="text/javascript" src="../../lib/jquery/CLEeditor/jquery.cleditor.min.js"></script>
    		<script type="text/javascript" src="../../lib/jquery/CLEeditor/startCLE.js"> </script>
			
			<!-- APP JS -->
			<script src="javascript/system.js"></script>
			
			<!-- APP css -->
			<link href="css/style.css" rel="stylesheet" />
			
		</head>
		
		<body>
		
		<!-- Disconnect form -->
		<form action="?application='<?= APPLICATION_NAME ?>'" method="post" name="disconnectForm" id="disconnectForm">
			<input type="hidden" name="disconnect" value="1" />
		</form>
	<?php }
	
	/**
	 * 
	 * Print the http footer
	 */
	public function getFooter(){ ?>
		</body>
		</html>
	<?php }
}
?>