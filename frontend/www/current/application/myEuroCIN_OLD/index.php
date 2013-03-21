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
<?php
	// page compression
	ob_start("ob_gzhandler");	
	// for the magic_quotes
	@set_magic_quotes_runtime(false);	
	
	// DEBUG
	ini_set('display_errors', 1);
	
	session_start();
	
	// GET ALL THE API KEYs AND ADDRESS
	require_once dirname(__FILE__).'/../../system/config.php';
	
	// IMPORT DICIONARY
	require_once dirname(__FILE__).'/dictionary.php';
	
	define('APPLICATION_NAME', "myEuroCIN");
	define('VISITOR_ID', "MYMED_myEurocin_visitor@yopmail.com");
	define('USER_CONNECTED', isset($_SESSION['user']));
	
	// CREATE THE HTML HEADER
	require_once dirname(__FILE__).'/TemplateManager.class.php';
	$template = new TemplateManager();
	$template->getHeader();
	
	// IMPORTS ALL THE HANDLER
	require_once dirname(__FILE__).'/controller/MyApplicationHandler.class.php';	$application = new MyApplicationHandler();
	require_once dirname(__FILE__).'/controller/LoginHandler.class.php';			$login = new LoginHandler();
	require_once dirname(__FILE__).'/controller/InscriptionHandler.class.php';		$inscription = new InscriptionHandler();
	require_once dirname(__FILE__).'/controller/MenuHandler.class.php';				new MenuHandler();
	
	// Default user -> Visitor
	if(!USER_CONNECTED){
		if(!isset($_SESSION['error'])){
			echo '<form action="#" method="post" name="signinForm" id="signinForm">';
			echo '<input type="hidden" name="signin" value="visitor" />';
			echo '</form>';
			echo '<script type="text/javascript">document.signinForm.submit();</script>';
		} else {
			echo '<script type="text/javascript">alert(\'Error during the login of the visitor user, please refresh the page and try again...\');</script>';
			unset($_SESSION['error']);
		}
	}
	
	// IMPORTS ALL THE VIEWS	
	require_once dirname(__FILE__).'/views/AbstractView.class.php';	
	require_once dirname(__FILE__).'/views/home/MainView.class.php';			new MainView();
	require_once dirname(__FILE__).'/views/home/FindView.class.php';			new FindView($application);
	require_once dirname(__FILE__).'/views/home/ResultView.class.php';			new ResultView($application);
	require_once dirname(__FILE__).'/views/home/DetailView.class.php';			new DetailView($application);
	require_once dirname(__FILE__).'/views/home/PublishView.class.php';			new PublishView($application);
	require_once dirname(__FILE__).'/views/home/ProfileView.class.php';			new ProfileView($login, $inscription);
	require_once dirname(__FILE__).'/views/home/UpdateProfileView.class.php';	new UpdateProfileView();
	require_once dirname(__FILE__).'/views/home/InscriptionView.class.php';		new InscriptionView();
	
	// CLOSE THE HTML PAGE
	$template->getFooter();
	
?>
