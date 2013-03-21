<?php

class NeedHelpController extends AuthenticatedController {

	
	public function handleRequest(){
		
		parent::handleRequest();
		
		/*
		 * Set an emergency flag to true
		 */
		$_SESSION['emergency'] = true;
		
		if ( isset($_SESSION['emergency_level']))
		{
			$nbOfBuddies = count($_SESSION['ExtendedProfile']->callingList);
			
			// increment the id of the buddy to call
			$_SESSION['emergency_level'] += 1;
			
			// If we have gone through the whole list, reset
			if ($_SESSION['emergency_level'] >= ($nbOfBuddies-1)){
				
				$_SESSION['emergency_level'] = 0;
			}
		}
		else {
			// Fill the first buddy to call
			$_SESSION['emergency_level'] = 0;
			
		}
			
		$_SESSION['emergency_next_phonecall'] = $_SESSION['ExtendedProfile']->callingList[$_SESSION['emergency_level']]->phone;
		
		$this->renderView("needHelp");
		
	}
	
	

}
?>