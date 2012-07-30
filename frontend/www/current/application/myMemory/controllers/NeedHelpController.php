<?php

class NeedHelpController extends AuthenticatedController {

	
	public function handleRequest(){
		
		parent::handleRequest();
		
		/*
		 * Set an emergency flag to true
		 */
		$_SESSION['emergency'] = true;
		
		/*
		 * Call the Notification models
		 * 
		 */
		
		// For test only : notification by e-mail
/*		
		$mail = new EmailNotification("m3phistos@gmail.com", 
										$_SESSION['user']->name, 
										"32 Rue Jean Jaurès, Cannes, France", 
										array(	"lat"=>"43.55353", 
												"lng"=>"7.02199"));
		$mail->send();
*/
		
		
		$this->renderView("needHelp");
		
	}
	
	

}
?>